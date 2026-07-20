<?php

namespace App\Filament\Client\Pages;

use Filament\Pages\Page;
use App\Models\Project;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\BriefAnswer;
use App\Models\ProjectCredential;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Livewire\WithFileUploads;
use Filament\Notifications\Notification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Illuminate\Support\HtmlString;

class Projects extends Page implements HasForms
{
    use WithFileUploads;
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-folder';
    protected static ?string $title = 'پروژه‌های من';
    protected string $view = 'filament.client.pages.projects';

    public ?Project $project = null;
    public int $progressPercent = 0;
    public string $statusLabel = '';

    public ?array $data = [];

    // Projects list and state management
    public $projects = [];
    public ?int $selectedProjectId = null;
    public string $activeTab = 'roadmap';

    // Contract signing properties
    public ?string $sigName = '';
    public ?string $sigNationalCode = '';

    // Payment upload properties
    public ?int $paymentAmount = null;
    public $bankSlipFile;

    // Feedback properties
    public ?string $feedbackNotes = '';

    public array $statuses = [
        'draft' => ['label' => 'پیش‌نویس اولیه', 'percent' => 10],
        'brief' => ['label' => 'تکمیل بریف نیازمندی‌ها', 'percent' => 25],
        'contract' => ['label' => 'امضای قرارداد و امور مالی', 'percent' => 45],
        'in_progress' => ['label' => 'در حال طراحی و توسعه', 'percent' => 65],
        'review' => ['label' => 'بازنگری و ثبت نظرات (دمو)', 'percent' => 80],
        'ready_handover' => ['label' => 'آماده‌سازی بسته تحویل', 'percent' => 90],
        'completed' => ['label' => 'تحویل نهایی و خاتمه پروژه', 'percent' => 100],
    ];

    public function mount(): void
    {
        $this->loadProjectsList();
        $this->form->fill();
    }

    public function loadProjectsList(): void
    {
        $this->projects = Project::where('client_id', Auth::id())
            ->latest()
            ->get();

        if ($this->selectedProjectId) {
            $this->loadProject($this->selectedProjectId);
        } else {
            $this->project = null;
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Wizard::make([
                    Wizard\Step::make('business_info')
                        ->label('مشخصات کسب‌وکار')
                        ->icon('heroicon-o-building-storefront')
                        ->schema([
                            TextInput::make('business_name')
                                ->label('نام برند / کسب‌وکار')
                                ->required()
                                ->placeholder('مثال: فروشگاه آنلاین هشت'),
                            Textarea::make('business_description')
                                ->label('توصیف خدمات و فعالیت کسب‌وکار')
                                ->required()
                                ->placeholder('توضیح دهید کسب‌وکار شما چه کار می‌کند...'),
                            Textarea::make('target_audience')
                                ->label('مخاطبان هدف')
                                ->placeholder('مشتریان شما چه کسانی هستند؟ (مثال: جوانان ۱۸ تا ۳۰ سال علاقه مند به تکنولوژی)'),
                            Textarea::make('competitors')
                                ->label('رقبای اصلی شما')
                                ->placeholder('نام یا آدرس وب‌سایت رقبای خود را بنویسید...'),
                        ]),

                    Wizard\Step::make('design_features')
                        ->label('طراحی و امکانات')
                        ->icon('heroicon-o-paint-brush')
                        ->schema([
                            Grid::make(2)->schema([
                                Select::make('design_style')
                                    ->label('سبک طراحی مورد پسند')
                                    ->options([
                                        'minimal' => 'مینیمال و ساده (سفند/خاکستری، فضای خالی زیاد)',
                                        'corporate' => 'شرکتی و رسمی (آبی، سرمه‌ای، ساختار منظم)',
                                        'modern_dark' => 'مدرن و تیره (فانتزی، مشکی/بنفش، جلوه‌های ویژه)',
                                        'creative' => 'خلاقانه و پویا (رنگارنگ، متحرک‌سازی زیاد)',
                                    ])
                                    ->required(),
                                TextInput::make('color_preferences')
                                    ->label('رنگ‌های سازمانی یا ترجیحی')
                                    ->placeholder('مثال: آبی آسمانی و خاکستری تیره'),
                            ]),
                            CheckboxList::make('features_required')
                                ->label('امکانات مورد نیاز وب‌سایت')
                                ->options([
                                    'e_commerce' => 'فروشگاه آنلاین و درگاه پرداخت',
                                    'blog' => 'وبلاگ و بخش اخبار/مقالات',
                                    'portfolio' => 'گالری تصاویر / نمونه کارها',
                                    'user_panel' => 'ثبت‌نام و پنل اختصاصی کاربران',
                                    'support_ticket' => 'سیستم تیکت پشتیبانی و چت آنلاین',
                                    'multi_language' => 'پشتیبانی از چند زبان',
                                    'custom' => 'سایر امکانات اختصاصی',
                                ])
                                ->columns(2),
                            Textarea::make('extra_notes')
                                ->label('توضیحات و نیازمندی‌های خاص')
                                ->placeholder('هرگونه توضیحات اضافی یا نمونه وب‌سایت‌هایی که می‌پسندید را در اینجا ذکر کنید...'),
                        ]),

                    Wizard\Step::make('credentials')
                        ->label('اطلاعات دسترسی (دارایی‌ها)')
                        ->icon('heroicon-o-key')
                        ->schema([
                            Section::make('دسترسی‌های هاست (میزبانی)')
                                ->collapsible()
                                ->schema([
                                    Grid::make(3)->schema([
                                        TextInput::make('host_provider')
                                            ->label('شرکت ارائه‌دهنده هاست')
                                            ->placeholder('مثال: ایران سرور، سون هاست'),
                                        TextInput::make('host_username')
                                            ->label('نام کاربری پنل هاست'),
                                        TextInput::make('host_password')
                                            ->label('کلمه عبور هاست')
                                            ->password()
                                            ->revealable(),
                                    ]),
                                    TextInput::make('host_panel_url')
                                        ->label('آدرس ورود به پنل هاست (cPanel/DirectAdmin/...)')
                                        ->url()
                                        ->placeholder('https://host.example.com:2083'),
                                ]),

                            Section::make('دسترسی‌های دامنه')
                                ->collapsible()
                                ->schema([
                                    Grid::make(3)->schema([
                                        TextInput::make('domain_provider')
                                            ->label('ثبت‌کننده دامنه')
                                            ->placeholder('مثال: ایرنیک، ایرپاور'),
                                        TextInput::make('domain_username')
                                            ->label('نام کاربری دامنه'),
                                        TextInput::make('domain_password')
                                            ->label('کلمه عبور دامنه')
                                            ->password()
                                            ->revealable(),
                                    ]),
                                    TextInput::make('domain_panel_url')
                                        ->label('آدرس ورود به پنل مدیریت دامنه')
                                        ->url()
                                        ->placeholder('https://nic.ir'),
                                ]),

                            Section::make('دسترسی‌های پنل مدیریت سایت فعلی (در صورت وجود)')
                                ->collapsible()
                                ->schema([
                                    Grid::make(3)->schema([
                                        TextInput::make('admin_panel_url')
                                            ->label('آدرس پنل مدیریت (WordPress/...)')
                                            ->url()
                                            ->placeholder('https://example.com/wp-admin'),
                                        TextInput::make('admin_username')
                                            ->label('نام کاربری مدیریت'),
                                        TextInput::make('admin_password')
                                            ->label('کلمه عبور مدیریت')
                                            ->password()
                                            ->revealable(),
                                    ]),
                                ]),

                            Section::make('سایر توضیحات دسترسی یا فایل‌های ضمیمه')
                                ->schema([
                                    Textarea::make('other_credentials')
                                        ->label('سایر اطلاعات دسترسی یا توضیحات فنی')
                                        ->placeholder('مثال: دسترسی به گوگل سرچ کنسول، کلودفلر و...'),
                                ]),
                        ]),
                ])
                ->submitAction(new HtmlString('
                    <button type="submit" class="fi-btn fi-btn-color-primary fi-color-primary fi-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 disabled:pointer-events-none disabled:opacity-70 bg-primary-600 hover:bg-primary-500 text-white shadow-sm fi-ac-btn-action px-4 py-2 rounded-lg text-sm">
                        ثبت و ارسال نهایی بریف
                    </button>
                '))
            ])
            ->statePath('data');
    }

    public function saveBrief(): void
    {
        $state = $this->form->getState();

        if (!$this->project) {
            Notification::make()->title('خطا')->body('پروژه‌ای یافت نشد.')->danger()->send();
            return;
        }

        // Save brief answers
        BriefAnswer::create([
            'project_id' => $this->project->id,
            'business_name' => $state['business_name'] ?? null,
            'business_description' => $state['business_description'] ?? null,
            'target_audience' => $state['target_audience'] ?? null,
            'competitors' => $state['competitors'] ?? null,
            'design_style' => $state['design_style'] ?? null,
            'color_preferences' => $state['color_preferences'] ?? null,
            'features_required' => $state['features_required'] ?? [],
            'extra_notes' => $state['extra_notes'] ?? null,
        ]);

        // Save credentials
        ProjectCredential::create([
            'project_id' => $this->project->id,
            'host_provider' => $state['host_provider'] ?? null,
            'host_username' => $state['host_username'] ?? null,
            'host_password' => $state['host_password'] ?? null,
            'host_panel_url' => $state['host_panel_url'] ?? null,
            'domain_provider' => $state['domain_provider'] ?? null,
            'domain_username' => $state['domain_username'] ?? null,
            'domain_password' => $state['domain_password'] ?? null,
            'domain_panel_url' => $state['domain_panel_url'] ?? null,
            'admin_panel_url' => $state['admin_panel_url'] ?? null,
            'admin_username' => $state['admin_username'] ?? null,
            'admin_password' => $state['admin_password'] ?? null,
            'other_credentials' => $state['other_credentials'] ?? null,
        ]);

        // Update project status to contract
        $this->project->update([
            'status' => 'contract',
        ]);

        // Send admin notification
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\ProjectNotification(
                $this->project,
                'ثبت بریف جدید',
                "مشتری برای پروژه «{$this->project->title}» پرسشنامه بریف و دسترسی‌ها را تکمیل نمود."
            ));
        }

        Notification::make()
            ->title('بریف و اطلاعات دسترسی با موفقیت ثبت شد')
            ->body('پروژه شما وارد مرحله امضای قرارداد و امور مالی گردید.')
            ->success()
            ->send();

        $this->loadProjectsList();
    }

    public string $renderedContractContent = '';

    protected function loadProject(?int $projectId = null): void
    {
        $projectId = $projectId ?: $this->selectedProjectId;

        if (!$projectId) {
            $this->project = null;
            return;
        }

        $this->project = Project::where('client_id', Auth::id())
            ->where('id', $projectId)
            ->with(['contract', 'payments', 'feedbacks', 'handover'])
            ->first();

        if ($this->project) {
            // Check feedback deadline auto-approval
            if ($this->project->status === 'review' && $this->project->feedback_deadline && Carbon::now()->gt($this->project->feedback_deadline)) {
                $this->project->update([
                    'status' => 'ready_handover',
                ]);
                $this->project->feedbacks()->create([
                    'notes' => 'تایید خودکار پروژه به دلیل به پایان رسیدن مهلت زمانی ارسال فیدبک.',
                    'status' => 'approved',
                ]);
                
                Notification::make()
                    ->title('تایید خودکار دمو')
                    ->body('مهلت فیدبک به پایان رسیده است؛ دمو به طور خودکار تایید شد.')
                    ->success()
                    ->send();

                // Reload project
                $this->loadProject($projectId);
                return;
            }

            $statusKey = $this->project->status;
            $statusInfo = $this->statuses[$statusKey] ?? ['label' => 'نامشخص', 'percent' => 0];
            $this->progressPercent = $statusInfo['percent'];
            $this->statusLabel = $statusInfo['label'];

            if ($this->project->contract) {
                $this->renderedContractContent = str_replace(
                    [':client_name', ':project_title', ':date'],
                    [Auth::user()->name ?? 'مشتری', $this->project->title, Carbon::now()->format('Y/m/d')],
                    $this->project->contract->content
                );
            }
        }
    }

    public function signContract(): void
    {
        $this->validate([
            'sigName' => 'required|string|min:3',
            'sigNationalCode' => 'required|digits:10',
        ], [
            'sigName.required' => 'وارد کردن نام الزامی است.',
            'sigNationalCode.required' => 'وارد کردن کد ملی الزامی است.',
            'sigNationalCode.digits' => 'کد ملی باید دقیقا ۱۰ رقم باشد.',
        ]);

        if (!$this->project || !$this->project->contract) {
            Notification::make()->title('خطا')->body('قراردادی برای این پروژه تنظیم نشده است.')->danger()->send();
            return;
        }

        $contract = $this->project->contract;
        $contract->update([
            'signed_at' => Carbon::now(),
            'signature_name' => $this->sigName,
            'signature_national_code' => $this->sigNationalCode,
        ]);

        Notification::make()
            ->title('قرارداد با موفقیت امضا شد')
            ->body('نسخه امضا شده در سیستم ثبت گردید.')
            ->success()
            ->send();

        $this->loadProjectsList();
    }

    public function uploadSlip(): void
    {
        $this->validate([
            'paymentAmount' => 'required|numeric|min:1000',
            'bankSlipFile' => 'required|image|max:2048', // max 2MB
        ], [
            'paymentAmount.required' => 'مبلغ واریزی الزامی است.',
            'bankSlipFile.required' => 'آپلود تصویر فیش الزامی است.',
            'bankSlipFile.image' => 'فایل ارسالی باید تصویر باشد.',
        ]);

        $filePath = $this->bankSlipFile->store('bank-slips', 'public');

        $payment = Payment::create([
            'project_id' => $this->project->id,
            'amount' => $this->paymentAmount,
            'bank_slip_path' => $filePath,
            'status' => 'pending',
        ]);

        // Send admin notification
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\ProjectNotification(
                $this->project,
                'ثبت فیش واریز جدید',
                "مشتری برای پروژه «{$this->project->title}» فیش واریزی جدیدی به مبلغ " . number_format($payment->amount) . " تومان بارگذاری کرد."
            ));
        }

        $this->paymentAmount = null;
        $this->bankSlipFile = null;

        Notification::make()
            ->title('فیش واریزی با موفقیت ثبت شد')
            ->body('فیش شما جهت تایید به مدیریت ارسال گردید.')
            ->success()
            ->send();

        $this->loadProjectsList();
    }

    public function submitFeedback(string $status): void
    {
        $this->validate([
            'feedbackNotes' => $status === 'needs_changes' ? 'required|string|min:5' : 'nullable|string',
        ], [
            'feedbackNotes.required' => 'وارد کردن توضیحات جهت اصلاحات الزامی است.',
            'feedbackNotes.min' => 'توضیحات اصلاحات باید حداقل ۵ کاراکتر باشد.',
        ]);

        if (!$this->project) return;

        $feedback = $this->project->feedbacks()->create([
            'notes' => $this->feedbackNotes ?: ($status === 'approved' ? 'تایید دمو بدون اصلاحات.' : ''),
            'status' => $status,
        ]);

        // Send admin notification
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\ProjectNotification(
                $this->project,
                $status === 'approved' ? 'تایید دمو توسط مشتری' : 'ثبت فیدبک اصلاحی دمو',
                $status === 'approved' 
                    ? "مشتری دموی پروژه «{$this->project->title}» را بدون اصلاحات تایید کرد."
                    : "مشتری نظرات اصلاحی جدیدی برای دموی پروژه «{$this->project->title}» ثبت نمود."
            ));
        }

        if ($status === 'approved') {
            $this->project->update([
                'status' => 'ready_handover',
            ]);
            Notification::make()
                ->title('دموی پروژه تایید شد')
                ->body('پروژه شما وارد مرحله آماده‌سازی بسته تحویل گردید.')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('نظرات شما ثبت شد')
                ->body('تیم پشتیبانی نظرات شما را بررسی خواهد کرد.')
                ->success()
                ->send();
        }

        $this->feedbackNotes = '';
        $this->loadProjectsList();
    }

    public function selectProject(int $projectId): void
    {
        $this->selectedProjectId = $projectId;
        $this->activeTab = 'roadmap';
        $this->loadProjectsList();
    }

    public function backToProjects(): void
    {
        $this->selectedProjectId = null;
        $this->project = null;
        $this->loadProjectsList();
    }

    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
    }
}
