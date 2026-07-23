<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Settings\SettingService;
use App\Services\Sms\SmsManager;
use App\Traits\NormalizesPhoneNumber;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\RateLimiter;

class OtpService
{
    use NormalizesPhoneNumber;

    protected SettingService $settingService;
    protected SmsManager $smsManager;

    public function __construct(SettingService $settingService, SmsManager $smsManager)
    {
        $this->settingService = $settingService;
        $this->smsManager = $smsManager;
    }

    /**
     * Check if sending OTP is rate limited for given phone and IP.
     */
    public function isRateLimited(string $phone, string $ip): bool
    {
        $maxAttempts = (int) $this->settingService->get('otp_rate_limit_max', 3);
        $decayMinutes = (int) $this->settingService->get('otp_rate_limit_decay', 2);

        $keyPhone = "otp_send:phone:{$phone}";
        $keyIp = "otp_send:ip:{$ip}";

        return RateLimiter::tooManyAttempts($keyPhone, $maxAttempts) ||
               RateLimiter::tooManyAttempts($keyIp, $maxAttempts * 2);
    }

    /**
     * Get remaining rate limit seconds.
     */
    public function getRateLimitSeconds(string $phone, string $ip): int
    {
        $keyPhone = "otp_send:phone:{$phone}";
        $keyIp = "otp_send:ip:{$ip}";

        return max(
            RateLimiter::availableIn($keyPhone),
            RateLimiter::availableIn($keyIp)
        );
    }

    /**
     * Generate, store, and send OTP code to user phone.
     */
    public function sendOtp(User $user, string $ip): array
    {
        $phone = $this->normalizePhoneNumber($user->phone);
        $user->phone = $phone;

        if ($this->isRateLimited($phone, $ip)) {
            $seconds = $this->getRateLimitSeconds($phone, $ip);
            return [
                'success' => false,
                'message' => "شما بیش از حد مجاز درخواست داده‌اید. لطفاً {$seconds} ثانیه دیگر مجدداً تلاش کنید.",
                'seconds' => $seconds,
            ];
        }

        // Generate OTP
        $length = (int) $this->settingService->get('otp_length', 5);
        $length = max(4, min(6, $length)); // 4 to 6 digits
        $min = pow(10, $length - 1);
        $max = pow(10, $length) - 1;
        $otpCode = (string) rand($min, $max);

        // Expiry minutes
        $expiryMinutes = (int) $this->settingService->get('otp_expiry_minutes', 5);

        // Save to user
        $user->otp_code = $otpCode;
        $user->otp_expires_at = Carbon::now()->addMinutes($expiryMinutes);
        $user->save();

        // Increment rate limiters
        $decayMinutes = (int) $this->settingService->get('otp_expiry_minutes', 2);
        RateLimiter::hit("otp_send:phone:{$phone}", $decayMinutes * 60);
        RateLimiter::hit("otp_send:ip:{$ip}", $decayMinutes * 60);

        // Send SMS via SmsManager (Pattern or plain text)
        $patternCode = $this->settingService->get('otp_pattern_code', '');
        $variableKey = $this->settingService->get('otp_variable_key', 'code');
        $driverName  = $this->settingService->get('otp_sms_driver', null);

        if (!empty($patternCode)) {
            $sent = $this->smsManager->sendPattern(
                $phone,
                $patternCode,
                [$variableKey => $otpCode],
                $driverName
            );
        } else {
            $message = "کد تایید ورود شما به سامانه هشت بهشت: {$otpCode}\nاعتبار: {$expiryMinutes} دقیقه";
            $sent = $this->smsManager->sendSMS($phone, $message, $driverName);
        }

        return [
            'success' => true,
            'message' => 'کد تایید با موفقیت ارسال شد.',
            'otp' => $otpCode, // return for testing UI toast when in debug
            'seconds' => 120, // default timer duration for UI countdown
        ];
    }

    /**
     * Verify user OTP code.
     */
    public function verifyOtp(User $user, string $inputCode): bool
    {
        $inputCode = $this->convertDigitsToEnglish($inputCode);

        if (empty($user->otp_code) || empty($user->otp_expires_at)) {
            return false;
        }

        if (Carbon::parse($user->otp_expires_at)->isPast()) {
            return false;
        }

        if ($user->otp_code !== trim($inputCode)) {
            return false;
        }

        // Invalidate OTP on successful verification
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        return true;
    }
}
