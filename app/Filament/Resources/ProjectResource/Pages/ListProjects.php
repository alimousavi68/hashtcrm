<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\Url;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;
    protected string $view = 'filament.resources.project-resource.pages.list-projects';

    #[Url]
    public string $search = '';

    #[Url]
    public ?string $activeTab = 'all';

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function getProjectsData(): array
    {
        $query = Project::with(['client'])->latest();

        // 1. فیلتر تب‌ها
        if ($this->activeTab === 'overdue') {
            $query->where('status', '!=', 'completed')
                ->whereNotNull('feedback_deadline')
                ->where('feedback_deadline', '<', now());
        } elseif ($this->activeTab === 'in_progress') {
            $query->whereIn('status', ['in_progress', 'review']);
        } elseif ($this->activeTab === 'brief_contract') {
            $query->whereIn('status', ['brief', 'contract']);
        } elseif ($this->activeTab === 'unsettled') {
            $query->where('is_settled', false);
        } elseif ($this->activeTab === 'completed') {
            $query->where('status', 'completed');
        }

        // 2. فیلتر جستجو
        if (!empty($this->search)) {
            $search = trim($this->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        return $query->get()->map(function (Project $p) {
            $percent = $p->getProgressPercent();
            $remainingPercent = 100 - $percent;

            $daysRemaining = null;
            $isOverdue = false;
            if ($p->feedback_deadline) {
                $now = now();
                $deadline = \Carbon\Carbon::parse($p->feedback_deadline);
                $diff = (int) $now->diffInDays($deadline, false);
                if ($diff < 0) {
                    $isOverdue = true;
                    $daysRemaining = \App\Helpers\JalaliHelper::toPersianDigits((string) abs($diff));
                } else {
                    $daysRemaining = \App\Helpers\JalaliHelper::toPersianDigits((string) $diff);
                }
            }

            return [
                'id' => $p->id,
                'title' => $p->title,
                'status' => $p->status,
                'status_label' => $p->getStatusLabel(),
                'client_name' => $p->client?->name ?? 'نامشخص',
                'client_phone' => $p->client?->phone ?? '',
                'progress_percent' => \App\Helpers\JalaliHelper::toPersianDigits((string) $percent),
                'remaining_percent' => \App\Helpers\JalaliHelper::toPersianDigits((string) $remainingPercent),
                'raw_progress' => $percent,
                'is_settled' => (bool) $p->is_settled,
                'demo_url' => $p->demo_url,
                'days_remaining' => $daysRemaining,
                'is_overdue' => $isOverdue,
                'created_jalali' => \App\Helpers\JalaliHelper::toJalali($p->created_at, 'Y/m/d'),
            ];
        })->toArray();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('تعریف پروژه جدید')
                ->icon('heroicon-o-plus'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\ProjectsOverviewStatsWidget::class,
        ];
    }
}
