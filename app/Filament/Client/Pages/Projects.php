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
        $this->projects = Project::where('client_id', Auth::guard('client')->id())
            ->latest()
            ->get();

        if (!$this->selectedProjectId && $this->projects->count() === 1) {
            $this->selectedProjectId = $this->projects->first()->id;
        }

        if ($this->selectedProjectId) {
            $this->loadProject($this->selectedProjectId);
        } else {
            $this->project = null;
        }
    }

    // Form and saveBrief methods have been removed. 
    // The brief is now handled by the CompleteBrief page dynamically.

    public string $renderedContractContent = '';

    protected function loadProject(?int $projectId = null): void
    {
        $projectId = $projectId ?: $this->selectedProjectId;

        if (!$projectId) {
            $this->project = null;
            return;
        }

        $this->project = Project::where('client_id', Auth::guard('client')->id())
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
                    [Auth::guard('client')->user()->name ?? 'مشتری', $this->project->title, Carbon::now()->format('Y/m/d')],
                    $this->project->contract->content
                );
            }
        }
    }

    public function getNextActionProperty(): ?array
    {
        if (!$this->project) return null;

        $status = $this->project->status;
        $contract = $this->project->contract;

        switch ($status) {
            case 'draft':
                return [
                    'title' => 'پروژه در حال آماده‌سازی اولیه',
                    'description' => 'تیم ما در حال تنظیم اولیه مشخصات پروژه شماست. به محض فعال‌سازی فاز بریف، به شما اطلاع داده خواهد شد.',
                    'buttonText' => null,
                    'actionType' => 'none',
                    'badge' => 'در حال تنظیم',
                    'color' => 'gray',
                ];

            case 'brief':
                return [
                    'title' => 'تکمیل فرم بریف نیازمندی‌های پروژه',
                    'description' => 'برای شروع فرآیند کدنویسی و تحلیل، لطفاً مشخصات برند و نیازمندی‌های سیستم را در فرم بریف اختصاصی وارد کنید.',
                    'buttonText' => 'تکمیل پرسشنامه بریف',
                    'actionType' => 'url',
                    'url' => \App\Filament\Client\Pages\CompleteBrief::getUrl(),
                    'badge' => 'اقدام فوری کارفرما',
                    'color' => 'amber',
                ];

            case 'contract':
                if (!$contract || !$contract->signed_at) {
                    return [
                        'title' => 'امضا و پذیرش آنلاین قرارداد همکاری',
                        'description' => 'متن قرارداد پروژه تنظیم گردیده است. لطفاً جهت رسمی شدن فاز اجرا، قرارداد را بررسی و امضای الکترونیک نمایید.',
                        'buttonText' => 'مشاهده و امضای قرارداد',
                        'actionType' => 'tab',
                        'tab' => 'finance',
                        'badge' => 'نیازمند امضا',
                        'color' => 'indigo',
                    ];
                }
                return [
                    'title' => 'ثبت فیش پرداخت پیش‌پرداخت',
                    'description' => 'قرارداد با موفقیت امضا شد. لطفاً تصویر فیش واریز پیش‌پرداخت را جهت تایید مالی بارگذاری کنید.',
                    'buttonText' => 'بارگذاری فیش بانکی',
                    'actionType' => 'tab',
                    'tab' => 'finance',
                    'badge' => 'اقدام مالی',
                    'color' => 'blue',
                ];

            case 'in_progress':
                return [
                    'title' => 'پروژه در حال طراحی و توسعه فنی است',
                    'description' => 'کدنویسی و پیاده‌سازی پروژه بر اساس بریف در حال انجام است. می‌توانید پیشرفت فازها را از نقشه راه دنبال کنید.',
                    'buttonText' => 'مشاهده نقشه راه',
                    'actionType' => 'tab',
                    'tab' => 'roadmap',
                    'badge' => 'در حال انجام',
                    'color' => 'sky',
                ];

            case 'review':
                return [
                    'title' => 'بررسی و تایید دموی زنده پروژه',
                    'description' => 'نسخه آزمایشی (دمو) پروژه شما منتشر شده است. لطفاً آن را تست کرده و نظرات اصلاحی یا تایید نهایی را ثبت نمایید.',
                    'buttonText' => 'بررسی دمو و ثبت نظرات',
                    'actionType' => 'tab',
                    'tab' => 'demo',
                    'badge' => 'بازنگری کارفرما',
                    'color' => 'purple',
                ];

            case 'ready_handover':
                if (!$this->project->is_settled) {
                    return [
                        'title' => 'تسویه حساب مالی جهت دریافت بسته تحویل',
                        'description' => 'توسعه پروژه پایان یافته است. جهت آزادسازی دسترسی‌های امن و فایل‌های تحویل، نسبت به تسویه حساب اقدام کنید.',
                        'buttonText' => 'مشاهده سوابق و پرداخت',
                        'actionType' => 'tab',
                        'tab' => 'finance',
                        'badge' => 'نیازمند تسویه',
                        'color' => 'amber',
                    ];
                }
                return [
                    'title' => 'دریافت بسته تحویل نهایی و آموزش‌ها',
                    'description' => 'پروژه شما کاملاً آماده است! دسترسی‌های هاست، دامنه و فایل‌های آموزشی در این بخش در دسترس شما قرار دارد.',
                    'buttonText' => 'مشاهده بسته تحویل',
                    'actionType' => 'tab',
                    'tab' => 'handover',
                    'badge' => 'تحویل پروژه',
                    'color' => 'emerald',
                ];

            case 'completed':
                return [
                    'title' => 'پروژه با موفقیت تکمیل و تحویل داده شد',
                    'description' => 'از اعتماد شما سپاسگزاریم. کلیه اطلاعات و ویدیوهای آموزشی در بسته تحویل نهایی دائم حفظ می‌شود.',
                    'buttonText' => 'مشاهده بسته تحویل',
                    'actionType' => 'tab',
                    'tab' => 'handover',
                    'badge' => 'خاتمه‌یافته',
                    'color' => 'emerald',
                ];

            default:
                return null;
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
                "مشتری برای پروژه «{$this->project->title}» فیش واریزی جدیدی به مبلغ " . number_format($payment->amount) . " تومان بارگذاری کرد.",
                'financial'
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
                    : "مشتری نظرات اصلاحی جدیدی برای دموی پروژه «{$this->project->title}» ثبت نمود.",
                'projects'
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
