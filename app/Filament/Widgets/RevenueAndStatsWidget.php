<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use App\Models\Project;
use App\Models\Ticket;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class RevenueAndStatsWidget extends Widget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 1,
        'xl' => 2,
    ];

    protected string $view = 'filament.widgets.revenue-and-stats-widget';

    public function getWidgetData(): array
    {
        // 1. پروژه‌ها
        $activeProjectsCount = Project::where('status', '!=', 'completed')->count();
        $totalProjectsCount = Project::count();

        // 2. واریزی‌های منتظر تایید
        $pendingPaymentsCount = Payment::whereNull('verified_at')->count();
        $pendingPaymentsSum = Payment::whereNull('verified_at')->sum('amount');

        // 3. تیکت‌ها
        $openTicketsCount = Ticket::where('status', '!=', 'closed')->count();

        // 4. آمار درآمدها (ماه جاری، سال جاری، کل درآمد، تایید نشده)
        $now = Carbon::now();
        $monthlyRevenue = Payment::whereNotNull('verified_at')
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->sum('amount');

        $yearlyRevenue = Payment::whereNotNull('verified_at')
            ->whereYear('created_at', $now->year)
            ->sum('amount');

        $totalRevenue = Payment::whereNotNull('verified_at')->sum('amount');

        $unverifiedRevenue = Payment::whereNull('verified_at')->sum('amount');

        // 5. بریف‌های در انتظار تکمیل (گلوگاه عملیاتی)
        $pendingBriefsCount = Project::where('status', 'brief')->count();

        return [
            'active_projects' => \App\Helpers\JalaliHelper::toPersianDigits((string) $activeProjectsCount),
            'total_projects' => \App\Helpers\JalaliHelper::toPersianDigits((string) $totalProjectsCount),
            
            'pending_payments_count' => \App\Helpers\JalaliHelper::toPersianDigits((string) $pendingPaymentsCount),
            'pending_payments_sum' => \App\Helpers\JalaliHelper::toPersianDigits(number_format($pendingPaymentsSum)),

            'open_tickets' => \App\Helpers\JalaliHelper::toPersianDigits((string) $openTicketsCount),

            'monthly_revenue' => \App\Helpers\JalaliHelper::toPersianDigits(number_format($monthlyRevenue)),
            'yearly_revenue' => \App\Helpers\JalaliHelper::toPersianDigits(number_format($yearlyRevenue)),
            'total_revenue' => \App\Helpers\JalaliHelper::toPersianDigits(number_format($totalRevenue)),
            'unverified_revenue' => \App\Helpers\JalaliHelper::toPersianDigits(number_format($unverifiedRevenue)),
            'pending_briefs' => \App\Helpers\JalaliHelper::toPersianDigits((string) $pendingBriefsCount),
            'jalali_month' => \App\Helpers\JalaliHelper::toJalali($now, 'F Y'),
        ];
    }
}
