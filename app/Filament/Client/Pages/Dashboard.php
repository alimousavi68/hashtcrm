<?php

namespace App\Filament\Client\Pages;

use Filament\Pages\Page;
use App\Models\Project;
use App\Models\Contract;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Livewire\WithFileUploads;
use Filament\Notifications\Notification;

class Dashboard extends Page
{
    use WithFileUploads;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'میز کار مشتری';
    protected static string $view = 'filament.client.pages.dashboard';

    public ?Project $project = null;
    public int $progressPercent = 0;
    public string $statusLabel = '';

    // Contract signing properties
    public ?string $sigName = '';
    public ?string $sigNationalCode = '';

    // Payment upload properties
    public ?int $paymentAmount = null;
    public $bankSlipFile;

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
        $this->loadProject();
    }

    public string $renderedContractContent = '';

    protected function loadProject(): void
    {
        $this->project = Project::where('client_id', Auth::id())
            ->with(['contract', 'payments'])
            ->latest()
            ->first();

        if ($this->project) {
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

        $this->loadProject();
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

        Payment::create([
            'project_id' => $this->project->id,
            'amount' => $this->paymentAmount,
            'bank_slip_path' => $filePath,
            'status' => 'pending',
        ]);

        $this->paymentAmount = null;
        $this->bankSlipFile = null;

        Notification::make()
            ->title('فیش واریزی با موفقیت ثبت شد')
            ->body('فیش شما جهت تایید به مدیریت ارسال گردید.')
            ->success()
            ->send();

        $this->loadProject();
    }
}
