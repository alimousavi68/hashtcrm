<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use App\Models\Project;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 2,
    ];

    protected function getStats(): array
    {
        // 1. پروژه‌های فعال
        $activeProjectsCount = Project::query()
            ->where('status', '!=', 'completed')
            ->count();

        // 2. واریزی‌های منتظر تایید
        $pendingPaymentsCount = Payment::query()
            ->whereNull('verified_at')
            ->count();
            
        $pendingPaymentsSum = Payment::query()
            ->whereNull('verified_at')
            ->sum('amount');

        // 3. تیکت‌های باز پشتیبانی
        $openTicketsCount = Ticket::query()
            ->where('status', '!=', 'closed')
            ->count();

        // 4. درآمد تاییدشده ماه جاری
        $monthlyRevenue = Payment::query()
            ->whereNotNull('verified_at')
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('amount');

        return [
            Stat::make('پروژه‌های در حال اجرا', number_format($activeProjectsCount) . ' پروژه')
                ->description('پروژه‌های فعال در حال توسعه یا بازنگری')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),

            Stat::make('فیش‌های مالی منتظر تایید', number_format($pendingPaymentsCount) . ' فیش')
                ->description('مجموع: ' . number_format($pendingPaymentsSum) . ' تومان')
                ->descriptionIcon($pendingPaymentsCount > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($pendingPaymentsCount > 0 ? 'warning' : 'success'),

            Stat::make('تیکت‌های باز و پشتیبانی', number_format($openTicketsCount) . ' تیکت')
                ->description('تیکت‌های در انتظار پاسخ یا پیگیری')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color($openTicketsCount > 0 ? 'danger' : 'gray'),

            Stat::make('درآمد تاییدشده (ماه جاری)', number_format($monthlyRevenue) . ' تومان')
                ->description('واریزی‌های تاییدشده در ' . \App\Helpers\JalaliHelper::toJalali(Carbon::now(), 'F Y'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
