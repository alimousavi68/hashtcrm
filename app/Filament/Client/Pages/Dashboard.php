<?php

namespace App\Filament\Client\Pages;

use Filament\Pages\Page;
use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'داشبورد';
    protected string $view = 'filament.client.pages.dashboard';

    public int $totalProjects = 0;
    public int $activeProjects = 0;
    public int $completedProjects = 0;
    public int $openTickets = 0;
    public ?Project $latestProject = null;
    public ?array $nextAction = null;

    public function mount(): void
    {
        $clientId = Auth::guard('client')->id();

        $this->totalProjects = Project::where('client_id', $clientId)->count();
        $this->activeProjects = Project::where('client_id', $clientId)->where('status', '!=', 'completed')->count();
        $this->completedProjects = Project::where('client_id', $clientId)->where('status', 'completed')->count();
        $this->openTickets = Ticket::where('client_id', $clientId)->where('status', 'open')->count();

        $this->latestProject = Project::where('client_id', $clientId)
            ->where('status', '!=', 'completed')
            ->latest()
            ->first() ?: Project::where('client_id', $clientId)->latest()->first();

        if ($this->latestProject) {
            $this->nextAction = $this->calculateNextAction($this->latestProject);
        }
    }

    protected function calculateNextAction(Project $project): ?array
    {
        $status = $project->status;
        $contract = $project->contract;

        switch ($status) {
            case 'draft':
                return [
                    'title' => 'پروژه در حال آماده‌سازی اولیه',
                    'description' => 'تیم ما در حال تنظیم اولیه مشخصات پروژه شماست.',
                    'buttonText' => null,
                    'actionType' => 'none',
                    'badge' => 'در حال تنظیم',
                    'color' => 'gray',
                ];

            case 'brief':
                return [
                    'title' => 'تکمیل فرم بریف نیازمندی‌های پروژه',
                    'description' => 'لطفاً مشخصات برند و نیازمندی‌های سیستم را در فرم بریف اختصاصی وارد کنید.',
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
                        'description' => 'متن قرارداد پروژه تنظیم گردیده است. لطفاً قرارداد را بررسی و امضای الکترونیک نمایید.',
                        'buttonText' => 'مشاهده و امضای قرارداد',
                        'actionType' => 'url',
                        'url' => route('filament.client.pages.projects'),
                        'badge' => 'نیازمند امضا',
                        'color' => 'indigo',
                    ];
                }
                return [
                    'title' => 'ثبت فیش پرداخت پیش‌پرداخت',
                    'description' => 'لطفاً تصویر فیش واریز پیش‌پرداخت را جهت تایید مالی بارگذاری کنید.',
                    'buttonText' => 'بارگذاری فیش بانکی',
                    'actionType' => 'url',
                    'url' => route('filament.client.pages.projects'),
                    'badge' => 'اقدام مالی',
                    'color' => 'blue',
                ];

            case 'in_progress':
                return [
                    'title' => 'پروژه در حال طراحی و توسعه فنی است',
                    'description' => 'کدنویسی و پیاده‌سازی پروژه بر اساس بریف در حال انجام است.',
                    'buttonText' => 'مشاهده پیشرفت پروژه',
                    'actionType' => 'url',
                    'url' => route('filament.client.pages.projects'),
                    'badge' => 'در حال انجام',
                    'color' => 'sky',
                ];

            case 'review':
                return [
                    'title' => 'بررسی و تایید دموی زنده پروژه',
                    'description' => 'نسخه آزمایشی (دمو) پروژه شما منتشر شده است. لطفاً آن را تست کرده و نظرات را ثبت کنید.',
                    'buttonText' => 'بررسی دمو و ثبت نظرات',
                    'actionType' => 'url',
                    'url' => route('filament.client.pages.projects'),
                    'badge' => 'بازنگری کارفرما',
                    'color' => 'purple',
                ];

            case 'ready_handover':
                if (!$project->is_settled) {
                    return [
                        'title' => 'تسویه حساب مالی جهت دریافت بسته تحویل',
                        'description' => 'جهت آزادسازی دسترسی‌های امن و فایل‌های تحویل، نسبت به تسویه حساب اقدام کنید.',
                        'buttonText' => 'مشاهده سوابق مالی',
                        'actionType' => 'url',
                        'url' => route('filament.client.pages.projects'),
                        'badge' => 'نیازمند تسویه',
                        'color' => 'amber',
                    ];
                }
                return [
                    'title' => 'دریافت بسته تحویل نهایی و آموزش‌ها',
                    'description' => 'پروژه شما آماده است! دسترسی‌های هاست و فایل‌های آموزشی در دسترس شماست.',
                    'buttonText' => 'مشاهده بسته تحویل',
                    'actionType' => 'url',
                    'url' => route('filament.client.pages.projects'),
                    'badge' => 'تحویل پروژه',
                    'color' => 'emerald',
                ];

            case 'completed':
                return [
                    'title' => 'پروژه با موفقیت تکمیل شد',
                    'description' => 'کلیه اطلاعات و ویدیوهای آموزشی در بسته تحویل نهایی دائم حفظ می‌شود.',
                    'buttonText' => 'مشاهده بسته تحویل',
                    'actionType' => 'url',
                    'url' => route('filament.client.pages.projects'),
                    'badge' => 'خاتمه‌یافته',
                    'color' => 'emerald',
                ];

            default:
                return null;
        }
    }
}
