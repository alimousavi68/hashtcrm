<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;

class AggregateProjectProgressWidget extends ChartWidget
{
    protected static ?int $sort = 2;
    protected ?string $heading = 'تحلیل تجمعی پیشرفت و کار باقیمانده';
    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 1,
    ];
    protected ?string $maxHeight = '200px';

    protected function getData(): array
    {
        $projects = Project::where('status', '!=', 'completed')->get();
        $totalProjects = $projects->count();

        if ($totalProjects === 0) {
            return [
                'datasets' => [
                    [
                        'label' => 'درصد پیشرفت',
                        'data' => [100],
                        'backgroundColor' => ['#10b981'],
                    ],
                ],
                'labels' => ['همه پروژه‌ها تکمیل شده‌اند'],
            ];
        }

        // محاسبه میانگین درصد پیشرفت و کار باقیمانده
        $sumPercent = 0;
        $statusCounts = [
            'draft' => 0,
            'brief' => 0,
            'contract' => 0,
            'in_progress' => 0,
            'review' => 0,
            'ready_handover' => 0,
        ];

        foreach ($projects as $project) {
            $sumPercent += $project->getProgressPercent();
            $statusCounts[$project->status] = ($statusCounts[$project->status] ?? 0) + 1;
        }

        $averageProgress = (int) round($sumPercent / $totalProjects);
        $remainingWork = 100 - $averageProgress;

        return [
            'datasets' => [
                [
                    'label' => 'درصد کل کار سیستم',
                    'data' => [
                        $averageProgress,
                        $remainingWork,
                    ],
                    'backgroundColor' => [
                        '#4f46e5', // پیشرفت انجام شده (بنفش/نیلی)
                        '#f43f5e', // کار باقیمانده (رز/قرمز)
                    ],
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => [
                "میزان پیشرفت میانگین کل (٪{$averageProgress})",
                "میزان کار باقیمانده سیستم (٪{$remainingWork})",
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'font' => [
                            'family' => 'Vazirmatn, Tahoma',
                            'size' => 12,
                        ],
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
