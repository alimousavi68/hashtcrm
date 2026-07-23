<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use App\Models\User;
use App\Services\Auth\OtpService;
use App\Traits\NormalizesPhoneNumber;

class CustomLogin extends BaseLogin
{
    use NormalizesPhoneNumber;

    public ?string $phone = '';
    public ?string $otp = '';
    public bool $otpSent = false;
    public int $countdownSeconds = 120;

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'ورود به سامانه مدیریت پروژه‌های هشت بهشت';
    }

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return new \Illuminate\Support\HtmlString('
            <div class="text-center space-y-1">
                <div class="text-2xl font-black text-primary-600 dark:text-primary-400 tracking-tight">هشت بهشت</div>
                <div class="text-xs font-bold text-gray-500 dark:text-gray-400">سامانه مدیریت پروژه‌ها</div>
            </div>
        ');
    }

    public function mount(): void
    {
        parent::mount();

        if (filament()->auth()->check()) {
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
            ->extraInputAttributes(['style' => 'direction: ltr; text-align: left; font-size: 1.1rem; font-weight: 600;']);
    }

    protected function getOtpFormComponent(): Component
    {
        return TextInput::make('otp')
            ->label('کد تایید (OTP)')
            ->required($this->otpSent)
            ->visible(fn () => $this->otpSent)
            ->placeholder('12345')
            ->extraInputAttributes([
                'style' => 'direction: ltr; text-align: center; font-weight: 800; letter-spacing: 0.6em; font-size: 1.4rem;',
                'maxlength' => 6,
                'autocomplete' => 'one-time-code',
            ]);
    }

    public function resetPhone(): void
    {
        $this->otpSent = false;
        $this->otp = '';
    }

    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();
        $panelId = filament()->getCurrentPanel()->getId();
        $otpService = app(OtpService::class);
        $ip = request()->ip();

        if (!$this->otpSent) {
            // Step 1: Normalize phone and validate user
            $phone = $this->normalizePhoneNumber($data['phone'] ?? '');

            if (empty($phone) || strlen($phone) < 10) {
                Notification::make()
                    ->title('خطا در شماره موبایل')
                    ->body('لطفاً یک شماره موبایل معتبر ۱۱ رقمی وارد نمایید.')
                    ->danger()
                    ->send();
                return null;
            }

            // Find user
            $user = User::where('phone', $phone)->first();

            if ($panelId === 'admin') {
                if (!$user || $user->role !== 'admin') {
                    Notification::make()
                        ->title('خطا در ورود')
                        ->body('شماره موبایل وارد شده در سیستم ثبت نشده یا دسترسی مدیریت ندارد.')
                        ->danger()
                        ->send();
                    return null;
                }
            } else { // client panel
                if ($user && $user->role !== 'client') {
                    Notification::make()
                        ->title('خطا در ورود')
                        ->body('این شماره همراه متعلق به مدیر سیستم است. لطفاً از پنل مدیریت وارد شوید.')
                        ->danger()
                        ->send();
                    return null;
                }

                if (!$user) {
                    // Create new client user
                    $user = User::create([
                        'phone' => $phone,
                        'name' => 'مشتری جدید',
                        'role' => 'client',
                    ]);
                }
            }

            // Send OTP via service
            $result = $otpService->sendOtp($user, $ip);

            if (!$result['success']) {
                Notification::make()
                    ->title('محدودیت ارسال')
                    ->body($result['message'])
                    ->warning()
                    ->send();
                return null;
            }

            // Notify user in UI
            Notification::make()
                ->title('کد تایید ارسال شد')
                ->body("کد تایید به شماره {$phone} ارسال گردید.")
                ->success()
                ->send();

            $this->otpSent = true;
            $this->countdownSeconds = $result['seconds'] ?? 120;
            $this->form->fill(['phone' => $phone]);

            return null;
        }

        // Step 2: Verify OTP
        $phone = $this->normalizePhoneNumber($data['phone'] ?? '');
        $otp = $this->convertDigitsToEnglish($data['otp'] ?? '');

        $user = User::where('phone', $phone)->first();

        if (!$user || !$otpService->verifyOtp($user, $otp)) {
            Notification::make()
                ->title('خطا در احراز هویت')
                ->body('کد وارد شده نامعتبر یا منقضی شده است.')
                ->danger()
                ->send();

            return null;
        }

        // Role verification
        if ($panelId === 'admin' && $user->role !== 'admin') {
            Notification::make()->title('خطا در ورود')->body('دسترسی مدیریت ندارید.')->danger()->send();
            return null;
        }
        if ($panelId === 'client' && $user->role !== 'client') {
            Notification::make()->title('خطا در ورود')->body('دسترسی مشتری ندارید.')->danger()->send();
            return null;
        }

        // Login user
        filament()->auth()->login($user);
        session()->regenerate();

        return app(LoginResponse::class);
    }
}
