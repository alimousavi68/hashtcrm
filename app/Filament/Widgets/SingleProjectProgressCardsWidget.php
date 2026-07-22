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

    public string $activeTab = 'all'; // all, overdue, in_dev, settled_pending

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function getProjectsData(): array
    {
        $query = Project::with(['client'])
            ->where('status', '!=', 'completed')
            ->latest();

        $projects = $query->get();

        $filtered = $projects->map(function (Project $project) {
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
        });

        // اعمال فیلتر بر اساس تب فعال
        if ($this->activeTab === 'overdue') {
            $filtered = $filtered->filter(fn ($p) => $p['is_overdue'] === true);
        } elseif ($this->activeTab === 'in_dev') {
            $filtered = $filtered->filter(fn ($p) => in_array($p['status_key'], ['in_progress', 'review']));
        } elseif ($this->activeTab === 'settled_pending') {
            $filtered = $filtered->filter(fn ($p) => $p['is_settled'] === false);
        }

        return $filtered->values()->toArray();
    }
}
