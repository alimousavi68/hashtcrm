<?php

namespace App\Filament\Widgets;

use App\Models\BriefTemplate;
use App\Models\BriefAnswer;
use Filament\Widgets\Widget;

class BriefTemplatesOverviewStatsWidget extends Widget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    protected string $view = 'filament.widgets.brief-templates-overview-stats-widget';

    public function getBriefStats(): array
    {
        $all = BriefTemplate::all();
        $totalCount = $all->count();
        $activeCount = $all->where('is_active', true)->count();
        $inactiveCount = $all->where('is_active', false)->count();
        $wizardCount = $all->where('wizard_mode', true)->count();

        $realAnswers = BriefAnswer::whereNotNull('dynamic_answers')->count();
        $sumResponses = $all->sum('responses_count');
        $totalResponses = max($realAnswers, $sumResponses);

        // Find largest template by question count
        $topTemplate = $all->sortByDesc(fn ($t) => count($t->schema ?? []))->first();

        return [
            'total_count' => \App\Helpers\JalaliHelper::toPersianDigits((string) $totalCount),
            'active_count' => \App\Helpers\JalaliHelper::toPersianDigits((string) $activeCount),
            'inactive_count' => \App\Helpers\JalaliHelper::toPersianDigits((string) $inactiveCount),
            'wizard_count' => \App\Helpers\JalaliHelper::toPersianDigits((string) $wizardCount),
            'total_responses' => \App\Helpers\JalaliHelper::toPersianDigits((string) $totalResponses),
            'top_template_name' => $topTemplate?->name ?? 'ثبت نشده',
            'top_questions_count' => \App\Helpers\JalaliHelper::toPersianDigits((string) count($topTemplate?->schema ?? [])),
        ];
    }
}
