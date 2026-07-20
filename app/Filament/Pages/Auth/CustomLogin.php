<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class CustomLogin extends BaseLogin
{
    public ?string $phone = '';
    public ?string $otp = '';
    public bool $otpSent = false;

    public function mount(): void
    {
        parent::mount();

        if (Auth::check()) {
            redirect()->intended(filament()->getUrl());
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                $this->getPhoneFormComponent(),
                $this->getOtpFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getPhoneFormComponent(): Component
    {
        return TextInput::make('phone')
            ->label('شماره موبایل')
            ->required()
            ->tel()
            ->placeholder('۰۹۱۲۳۴۵۶۷۸۹')
            ->readOnly(fn () => $this->otpSent)
            ->extraInputAttributes(['style' => 'direction: ltr; text-align: left;']);
    }

    protected function getOtpFormComponent(): Component
    {
        return TextInput::make('otp')
            ->label('کد تایید (OTP)')
            ->required($this->otpSent)
            ->visible(fn () => $this->otpSent)
            ->placeholder('12345')
            ->extraInputAttributes(['style' => 'direction: ltr; text-align: center; font-weight: bold; letter-spacing: 0.5em;']);
    }

    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        if (!$this->otpSent) {
            // Step 1: Send OTP
            $phone = $data['phone'];

            // Find or create user
            $user = User::firstOrCreate(
                ['phone' => $phone],
                [
                    'name' => 'مشتری جدید',
                    'role' => 'client',
                ]
            );

            // Generate OTP (5 digit simple code)
            $otpCode = rand(10000, 99999);
            $user->otp_code = $otpCode;
            $user->otp_expires_at = Carbon::now()->addMinutes(5);
            $user->save();

            // Simulate sending SMS (log it)
            logger()->info("OTP Code for user {$phone} is: {$otpCode}");

            // Notify user in UI
            Notification::make()
                ->title('کد تایید ارسال شد')
                ->body("کد تایید آزمایشی (فقط جهت تست): {$otpCode}")
                ->success()
                ->send();

            $this->otpSent = true;
            $this->form->fill(['phone' => $phone]); // keep phone value

            return null;
        }

        // Step 2: Verify OTP
        $phone = $data['phone'];
        $otp = $data['otp'];

        $user = User::where('phone', $phone)->first();

        if (!$user || $user->otp_code !== $otp || Carbon::parse($user->otp_expires_at)->isPast()) {
            Notification::make()
                ->title('خطا در احراز هویت')
                ->body('کد وارد شده نامعتبر یا منقضی شده است.')
                ->danger()
                ->send();

            return null;
        }

        // Reset OTP code
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        // Login user
        Auth::login($user);

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
