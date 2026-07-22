<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Payment;
use App\Models\Ticket;
use Filament\Widgets\Widget;

class ProjectsOverviewStatsWidget extends Widget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    protected string $view = 'filament.widgets.projects-overview-stats-widget';

    public function getProjectsStats(): array
    {
        $allProjects = Project::with(['client'])->get();
        $totalProjects = $allProjects->count();
        $activeProjects = $allProjects->where('status', '!=', 'completed');
        $activeCount = $activeProjects->count();
        $completedCount = $allProjects->where('status', 'completed')->count();

        // 1. فازهای فعال
        $phaseCounts = [
            'draft' => $allProjects->where('status', 'draft')->count(),
            'brief' => $allProjects->where('status', 'brief')->count(),
            'contract' => $allProjects->where('status', 'contract')->count(),
            'in_progress' => $allProjects->where('status', 'in_progress')->count(),
            'review' => $allProjects->where('status', 'review')->count(),
            'ready_handover' => $allProjects->where('status', 'ready_handover')->count(),
            'completed' => $completedCount,
        ];

        // 2. نیازمند اقدام (بریف معطل یا فیش منتظر تایید یا تیکت باز)
        $pendingPaymentsCount = Payment::whereNull('verified_at')->count();
        $openTicketsCount = Ticket::where('status', '!=', 'closed')->count();
        $pendingBriefsCount = $allProjects->where('status', 'brief')->count();
        $actionRequiredCount = $pendingBriefsCount + $pendingPaymentsCount + $openTicketsCount;

        // 3. ددلاین‌ها و تاخیر
        $now = now();
        $overdueCount = $allProjects->filter(function ($p) use ($now) {
            return $p->status !== 'completed' 
                && $p->feedback_deadline !== null 
                && $p->feedback_deadline->isPast();
        })->count();

        // 4. میانگین درصد پیشرفت کل پروژه‌های فعال
        $sumPercent = 0;
        foreach ($activeProjects as $p) {
            $sumPercent += $p->getProgressPercent();
        }
        $avgProgress = $activeCount > 0 ? (int) round($sumPercent / $activeCount) : 100;

        return [
            'total_projects' => \App\Helpers\JalaliHelper::toPersianDigits((string) $totalProjects),
            'active_count' => \App\Helpers\JalaliHelper::toPersianDigits((string) $activeCount),
            'completed_count' => \App\Helpers\JalaliHelper::toPersianDigits((string) $completedCount),
            'phase_counts' => $phaseCounts,
            
            'action_required_count' => \App\Helpers\JalaliHelper::toPersianDigits((string) $actionRequiredCount),
            'pending_briefs' => \App\Helpers\JalaliHelper::toPersianDigits((string) $pendingBriefsCount),
            'pending_payments' => \App\Helpers\JalaliHelper::toPersianDigits((string) $pendingPaymentsCount),
            'open_tickets' => \App\Helpers\JalaliHelper::toPersianDigits((string) $openTicketsCount),

            'overdue_count' => \App\Helpers\JalaliHelper::toPersianDigits((string) $overdueCount),
            'raw_overdue' => $overdueCount,

            'avg_progress' => \App\Helpers\JalaliHelper::toPersianDigits((string) $avgProgress),
            'raw_avg_progress' => $avgProgress,
        ];
    }
}
