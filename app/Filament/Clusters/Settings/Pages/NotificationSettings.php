<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\SettingsCluster;
use App\Services\Settings\SettingService;
use App\Services\Sms\SmsManager;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;

class NotificationSettings extends Page
{
    protected static ?string $cluster = SettingsCluster::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'درگاه پیامک و اعلانات';

    protected static ?string $title = 'مدیریت درگاه‌های پیامکی و الگوی اعلانات';

    protected string $view = 'filament.clusters.settings.pages.notification-settings';

    public ?array $data = [];
    public ?array $balanceData = null;

    public function mount(SettingService $settingService): void
    {
        $this->form->fill([
            'sms_default_driver' => $settingService->get('sms_default_driver', 'ippanel'),
            'sms_sandbox_mode'   => (bool) $settingService->get('sms_sandbox_mode', false),
            'ippanel_api_key'    => $settingService->get('ippanel_api_key', ''),
            'ippanel_originator' => $settingService->get('ippanel_originator', '3000505'),
            'pattern_welcome'    => $settingService->get('pattern_welcome', ''),
            'pattern_project_status' => $settingService->get('pattern_project_status', ''),
            'pattern_payment_reminder' => $settingService->get('pattern_payment_reminder', ''),
            'pattern_ticket_reply' => $settingService->get('pattern_ticket_reply', ''),
        ]);

        $this->fetchBalance();
    }

    public function fetchBalance(): void
    {
        try {
            $smsManager = app(SmsManager::class);
            $this->balanceData = $smsManager->getBalance();
        } catch (\Exception $e) {
            $this->balanceData = null;
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('وضعیت آنی اعتبار و درگاه')
                    ->description('اطلاعات موجودی حساب و وضعیت اتصال آنلاین به سرویس پیامکی.')
                    ->columnSpanFull()
                    ->schema([
                        ViewField::make('balance_cards')
                            ->columnSpanFull()
                            ->view('filament.clusters.settings.pages.widgets.balance-cards')
                            ->viewData([
                                'balanceData' => $this->balanceData,
                                'driver'      => $this->data['sms_default_driver'] ?? 'ippanel',
                                'sandbox'     => $this->data['sms_sandbox_mode'] ?? false,
                            ]),
                    ]),

                Section::make('انتخاب درگاه فعال پیامکی')
                    ->description('درگاه اصلی ارسال پیامک‌های سیستم و حالت تست را پیکربندی کنید.')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('sms_default_driver')
                                ->label('درگاه فعال پیش‌فرض')
                                ->options([
                                    'ippanel' => '🟢 IPPanel (ارسال انبوه و پترن سریع)',
                                    'log'     => '🟡 Log Driver (شبیه‌ساز پیامک در محیط تست)',
                                ])
                                ->required()
                                ->native(false),

                            Toggle::make('sms_sandbox_mode')
                                ->label('حالت ایزوله تست (Sandbox Mode)')
                                ->helperText('در صورت فعال‌سازی، هیچ پیامک واقعی ارسال نشده و همه پیامک‌ها لاگ می‌شوند.')
                                ->inline(false),
                        ]),
                    ]),

                Section::make('تنظیمات اختصاصی IPPanel')
                    ->description('اطلاعات احراز هویت و خط اختصاصی پنل IPPanel را وارد نمایید.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('ippanel_api_key')
                                ->label('کلید اتصال (API Key)')
                                ->password()
                                ->revealable()
                                ->placeholder('کلید API دریافتی از IPPanel')
                                ->required(fn ($get) => $get('sms_default_driver') === 'ippanel'),

                            TextInput::make('ippanel_originator')
                                ->label('شماره خط ارسال‌کننده (Originator)')
                                ->placeholder('3000505')
                                ->required(fn ($get) => $get('sms_default_driver') === 'ippanel')
                                ->extraInputAttributes(['style' => 'direction: ltr; text-align: left;']),
                        ]),
                    ]),

                Section::make('کدهای پترن پیامک‌های سیستم')
                    ->description('کدهای الگو (Pattern Code) تاییدشده در سامانه پیامکی را برای هر سناریو ثبت کنید.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('pattern_welcome')
                                ->label('کد پترن خوش‌آمدگویی مشتری')
                                ->placeholder('مثال: welcome_client'),

                            TextInput::make('pattern_project_status')
                                ->label('کد پترن تغییر وضعیت پروژه')
                                ->placeholder('مثال: project_update'),

                            TextInput::make('pattern_payment_reminder')
                                ->label('کد پترن یادآور پرداخت و صورتحساب')
                                ->placeholder('مثال: payment_notice'),

                            TextInput::make('pattern_ticket_reply')
                                ->label('کد پترن پاسخ به تیکت پشتیبانی')
                                ->placeholder('مثال: ticket_reply'),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(SettingService $settingService): void
    {
        $state = $this->form->getState();

        $settingService->set('sms_default_driver', $state['sms_default_driver'], 'sms');
        $settingService->set('sms_sandbox_mode', $state['sms_sandbox_mode'] ? '1' : '0', 'sms');
        $settingService->set('ippanel_api_key', $state['ippanel_api_key'], 'sms', true); // encrypted
        $settingService->set('ippanel_originator', $state['ippanel_originator'], 'sms');

        $settingService->set('pattern_welcome', $state['pattern_welcome'], 'sms');
        $settingService->set('pattern_project_status', $state['pattern_project_status'], 'sms');
        $settingService->set('pattern_payment_reminder', $state['pattern_payment_reminder'], 'sms');
        $settingService->set('pattern_ticket_reply', $state['pattern_ticket_reply'], 'sms');

        $this->fetchBalance();

        Notification::make()
            ->title('تنظیمات با موفقیت ذخیره شد')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('testConnection')
                ->label('بررسی صحت اتصال به درگاه')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->color('success')
                ->action(function (SmsManager $smsManager) {
                    $result = $smsManager->testConnection();
                    if ($result['success']) {
                        $this->fetchBalance();
                        Notification::make()
                            ->title('اتصال موفقیت‌آمیز')
                            ->body($result['message'])
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('خطا در اتصال به درگاه')
                            ->body($result['message'])
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }),

            Action::make('refreshBalance')
                ->label('استعلام آنی اعتبار')
                ->icon(Heroicon::OutlinedArrowPath)
                ->color('gray')
                ->action(function () {
                    $this->fetchBalance();
                    if ($this->balanceData) {
                        Notification::make()
                            ->title('اعتبار درگاه به روز شد')
                            ->body("موجودی حساب: " . number_format($this->balanceData['credit'] ?? 0) . " ریال")
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('خطا در استعلام اعتبار')
                            ->body('دریافت موجودی ناموفق بود. دکمه "بررسی صحت اتصال به درگاه" را بزنید.')
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('testSms')
                ->label('ارسال پیامک آزمایشی')
                ->icon(Heroicon::OutlinedPaperAirplane)
                ->color('warning')
                ->schema([
                    TextInput::make('test_phone')
                        ->label('شماره همراه دریافت‌کننده')
                        ->required()
                        ->placeholder('09123456789'),
                    Textarea::make('test_message')
                        ->label('متن پیامک آزمایشی')
                        ->required()
                        ->default('این یک پیامک آزمایشی از سامانه مدیریت پروژه‌های هشت بهشت است.'),
                ])
                ->action(function (array $data, SmsManager $smsManager) {
                    $sent = $smsManager->sendSMS($data['test_phone'], $data['test_message'], null, false);
                    if ($sent) {
                        Notification::make()
                            ->title('ارسال موفق')
                            ->body("پیامک آزمایشی با موفقیت تحویل درگاه IPPanel شد.")
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('خطا در ارسال پیامک')
                            ->body('ارسال پیامک آزمایشی با خطا مواجه شد. کلید API Key و خط ارسال‌کننده را بررسی کنید.')
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }),
        ];
    }
}
