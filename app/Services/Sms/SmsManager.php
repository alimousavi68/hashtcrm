<?php

namespace App\Services\Sms;

use App\Contracts\SmsGatewayInterface;
use App\Models\SmsLog;
use App\Services\Settings\SettingService;
use App\Services\Sms\Drivers\IpPanelDriver;
use App\Services\Sms\Drivers\LogDriver;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SmsManager
{
    protected SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Get instance of the active SMS driver.
     */
    public function driver(?string $driverName = null): SmsGatewayInterface
    {
        $sandboxMode = $this->settingService->get('sms_sandbox_mode', '0');
        if ($sandboxMode === '1' || $sandboxMode === true) {
            return app(LogDriver::class);
        }

        $activeDriver = $driverName ?? $this->settingService->get('sms_default_driver', 'ippanel');

        return match ($activeDriver) {
            'ippanel' => app(IpPanelDriver::class),
            'log'     => app(LogDriver::class),
            default   => app(LogDriver::class),
        };
    }

    /**
     * Test active driver connection directly.
     */
    public function testConnection(): array
    {
        $driver = $this->driver();
        if ($driver instanceof IpPanelDriver) {
            return $driver->testConnection();
        }

        return [
            'success' => true,
            'message' => 'درگاه فعال روی حالت شبیه‌ساز (Log Driver) یا تست قرار دارد.',
        ];
    }

    /**
     * Check if system-wide daily SMS limit has been exceeded.
     */
    public function isDailyLimitExceeded(): bool
    {
        $maxDaily = (int) $this->settingService->get('sms_daily_limit', 1000);
        if ($maxDaily <= 0) {
            return false;
        }

        $todayCount = SmsLog::whereDate('created_at', Carbon::today())
            ->where('status', 'sent')
            ->count();

        return $todayCount >= $maxDaily;
    }

    /**
     * Send plain SMS message.
     */
    public function sendSMS(string $to, string $message, ?string $driverName = null, bool $allowFallback = false): bool
    {
        if ($this->isDailyLimitExceeded()) {
            Log::warning("System-wide daily SMS limit exceeded ({$to})");
            return false;
        }

        $driver = $this->driver($driverName);
        $result = $driver->sendSMS($to, $message);

        if (!$result && $allowFallback && !($driver instanceof LogDriver)) {
            Log::warning("SMS primary driver failed. Falling back to LogDriver for {$to}");
            return app(LogDriver::class)->sendSMS($to, "[FALLBACK] " . $message);
        }

        return $result;
    }

    /**
     * Send pattern SMS message.
     */
    public function sendPattern(string $to, string $patternCode, array $inputData, ?string $driverName = null, bool $allowFallback = false): bool
    {
        if ($this->isDailyLimitExceeded()) {
            Log::warning("System-wide daily SMS limit exceeded for pattern ({$to})");
            return false;
        }

        $driver = $this->driver($driverName);
        $result = $driver->sendPattern($to, $patternCode, $inputData);

        if (!$result && $allowFallback && !($driver instanceof LogDriver)) {
            Log::warning("SMS primary driver pattern failed. Falling back to LogDriver for {$to}");
            return app(LogDriver::class)->sendPattern($to, $patternCode, $inputData);
        }

        return $result;
    }

    /**
     * Get live balance from active driver.
     */
    public function getBalance(): ?array
    {
        return $this->driver()->getBalance();
    }
}
