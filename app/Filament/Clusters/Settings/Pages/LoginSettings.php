<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\SettingsCluster;
use App\Services\Settings\SettingService;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

class LoginSettings extends Page
{
    protected static ?string $cluster = SettingsCluster::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static ?string $navigationLabel = 'تنظیمات ورود و OTP';

    protected static ?string $title = 'پیکربندی ورود با پیامک و پارامترهای OTP';

    protected string $view = 'filament.clusters.settings.pages.login-settings';

    public ?array $data = [];

    public function mount(SettingService $settingService): void
    {
        $this->form->fill([
            'otp_length'          => (int) $settingService->get('otp_length', 5),
            'otp_expiry_minutes'  => (int) $settingService->get('otp_expiry_minutes', 5),
            'otp_rate_limit_max'  => (int) $settingService->get('otp_rate_limit_max', 3),
            'otp_rate_limit_decay'=> (int) $settingService->get('otp_rate_limit_decay', 2),
            'otp_pattern_code'    => $settingService->get('otp_pattern_code', 'login_otp'),
            'otp_variable_key'    => $settingService->get('otp_variable_key', 'code'),
            'otp_sms_driver'      => $settingService->get('otp_sms_driver', 'ippanel'),
            'sms_daily_limit'     => (int) $settingService->get('sms_daily_limit', 1000),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('پارامترهای تولید کد یکبار مصرف (OTP)')
                    ->description('طول کد، مدت زمان اعتبار و کد پترن ارسال پیامک لاگین را تنظیم کنید.')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('otp_length')
                                ->label('طول کد تایید')
                                ->options([
                                    4 => '۴ رقم (مثال: ۱۲۳۴)',
                                    5 => '۵ رقم (پیش‌فرض: ۱۲۳۴۵)',
                                    6 => '۶ رقم (امنیتی: ۱۲۳۴۵۶)',
                                ])
                                ->required()
                                ->native(false),

                            TextInput::make('otp_expiry_minutes')
                                ->label('زمان اعتبار کد (دقیقه)')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(30)
                                ->required(),

                            Select::make('otp_sms_driver')
                                ->label('درایور اختصاصی OTP')
                                ->options([
                                    'ippanel' => 'IPPanel (توصیه‌شده)',
                                    'log'     => 'Log Driver (شبیه‌ساز)',
                                ])
                                ->required()
                                ->native(false),
                        ]),
                    ]),

                Section::make('الگو (Pattern) پیامک ورود')
                    ->description('شناسه پترن تاییدشده در سامانه پیامکی برای ارسال سریع OTP.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('otp_pattern_code')
                                ->label('کد پترن OTP در درگاه')
                                ->placeholder('مثال: otp_code'),

                            TextInput::make('otp_variable_key')
                                ->label('نام متغیر کد در پترن')
                                ->placeholder('پیش‌فرض: code')
                                ->helperText('متغیری که در متن پترن تعریف کرده‌اید (مثلاً %code%).'),
                        ]),
                    ]),

                Section::make('امنیتی و سقف مجاز ارسال (Rate Limiting & Safeguards)')
                    ->description('محدودیت درخواست‌های تکراری و سقف مصرف روزانه پیامک سیستم.')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('otp_rate_limit_max')
                                ->label('حداکثر تلاش مجاز در بازه')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(10)
                                ->helperText('تعداد دفعات مجاز ارسال کد برای یک شماره/IP')
                                ->required(),

                            TextInput::make('otp_rate_limit_decay')
                                ->label('بازه زمانی محدودیت (دقیقه)')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(60)
                                ->required(),

                            TextInput::make('sms_daily_limit')
                                ->label('سقف مجاز ارسال روزانه کل سیستم')
                                ->numeric()
                                ->helperText('جلوگیری از حملات اسپم و اتمام ناگهانی اعتبار')
                                ->required(),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(SettingService $settingService): void
    {
        $state = $this->form->getState();

        $settingService->set('otp_length', $state['otp_length'], 'login');
        $settingService->set('otp_expiry_minutes', $state['otp_expiry_minutes'], 'login');
        $settingService->set('otp_rate_limit_max', $state['otp_rate_limit_max'], 'login');
        $settingService->set('otp_rate_limit_decay', $state['otp_rate_limit_decay'], 'login');
        $settingService->set('otp_pattern_code', $state['otp_pattern_code'], 'login');
        $settingService->set('otp_variable_key', $state['otp_variable_key'], 'login');
        $settingService->set('otp_sms_driver', $state['otp_sms_driver'], 'login');
        $settingService->set('sms_daily_limit', $state['sms_daily_limit'], 'login');

        Notification::make()
            ->title('تنظیمات ورود با موفقیت بروزرسانی شد')
            ->success()
            ->send();
    }
}
