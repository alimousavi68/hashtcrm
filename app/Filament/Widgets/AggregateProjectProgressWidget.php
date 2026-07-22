<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;

class AggregateProjectProgressWidget extends ChartWidget
{
    protected static ?int $sort = 2;
    protected ?string $heading = null;
    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 1,
        'xl' => 1,
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

        $avgPersian = \App\Helpers\JalaliHelper::toPersianDigits((string) $averageProgress);
        $remPersian = \App\Helpers\JalaliHelper::toPersianDigits((string) $remainingWork);

        return [
            'datasets' => [
                [
                    'label' => 'درصد کل کار سیستم',
                    'data' => [
                        $averageProgress,
                        $remainingWork,
                    ],
                    'backgroundColor' => [
                        '#10b981', // پیشرفت انجام شده (سبز زمردی هارمونیک / Emerald 500)
                        '#cbd5e1', // کار باقیمانده (طوسی‌آبی خاکستری ملایم / Slate 300)
                    ],
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => [
                "پیشرفت کل: ٪{$avgPersian}",
                "باقیمانده: ٪{$remPersian}",
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
                        'color' => '#1e293b',
                        'font' => [
                            'family' => 'PeydaWebVF, Vazirmatn, Tahoma',
                            'size' => 12,
                            'weight' => '700',
                        ],
                        'padding' => 16,
                        'usePointStyle' => true,
                        'pointStyle' => 'circle',
                        'boxWidth' => 8,
                        'boxHeight' => 8,
                    ],
                ],
            ],
            'cutout' => '70%',
            'maintainAspectRatio' => false,
        ];
    }
}
