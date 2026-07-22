<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class SingleProjectProgressCardsWidget extends Widget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected string $view = 'filament.widgets.single-project-progress-cards';

    public function getProjectsData(): array
    {
        $projects = Project::with(['client'])
            ->where('status', '!=', 'completed')
            ->latest()
            ->get();

        return $projects->map(function (Project $project) {
            $progressPercent = $project->getProgressPercent();
            $remainingPercent = 100 - $progressPercent;

            // محاسبه زمان باقیمانده تا ددلاین فیدبک یا تحویل
            $daysRemaining = null;
            $deadlineFormatted = '—';
            $isOverdue = false;

            if ($project->feedback_deadline) {
                $deadlineDate = Carbon::parse($project->feedback_deadline);
                $now = Carbon::now();

                $deadlineFormatted = \App\Helpers\JalaliHelper::toJalali($project->feedback_deadline);

                if ($now->greaterThan($deadlineDate)) {
                    $isOverdue = true;
                    $daysRemaining = (int) ceil($now->diffInDays($deadlineDate, false));
                    if ($daysRemaining < 0) $daysRemaining = abs($daysRemaining);
                } else {
                    $daysRemaining = (int) ceil($now->diffInDays($deadlineDate));
                }
            }

            return [
                'id' => $project->id,
                'title' => $project->title,
                'client_name' => $project->client?->name ?? 'مشتری نامشخص',
                'status_label' => $project->getStatusLabel(),
                'status_key' => $project->status,
                'progress_percent' => $progressPercent,
                'remaining_percent' => $remainingPercent,
                'is_settled' => $project->is_settled,
                'deadline_formatted' => $deadlineFormatted,
                'days_remaining' => $daysRemaining,
                'is_overdue' => $isOverdue,
            ];
        })->toArray();
    }
}
