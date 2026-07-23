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
        if ($this->activeTab === 'all') {
            // Exclude drafts and cancelled from 'all active'
            $query->whereNotIn('status', ['draft', 'rejected', 'cancelled']);
        } elseif ($this->activeTab === 'leads') {
            $query->where('status', 'draft');
        } elseif ($this->activeTab === 'archived') {
            $query->whereIn('status', ['rejected', 'cancelled']);
        } elseif ($this->activeTab === 'overdue') {
            $query->whereNotIn('status', ['completed', 'rejected', 'cancelled'])
                ->whereNotNull('feedback_deadline')
                ->where('feedback_deadline', '<', now());
        } elseif ($this->activeTab === 'in_progress') {
            $query->whereIn('status', ['in_progress', 'ui_design', 'development', 'review']);
        } elseif ($this->activeTab === 'brief_contract') {
            $query->whereIn('status', ['brief', 'proforma', 'contract']);
        } elseif ($this->activeTab === 'unsettled') {
            $query->where('is_settled', false)->whereNotIn('status', ['rejected', 'cancelled']);
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
            Actions\Action::make('send_magic_link')
                ->label('تعریف لید جدید / ارسال لینک بریف')
                ->icon('heroicon-o-sparkles')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\Select::make('brief_template_id')
                        ->label('الگوی پرسشنامه بریف')
                        ->options(\App\Models\BriefTemplate::where('is_active', true)->pluck('name', 'id'))
                        ->default(fn () => \App\Models\BriefTemplate::where('is_default', true)->first()?->id ?? \App\Models\BriefTemplate::where('is_active', true)->first()?->id)
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('client_phone')
                        ->label('شماره موبایل مشتری')
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('client_name')
                        ->label('نام مشتری (اختیاری)')
                        ->helperText('فقط در صورت جدید بودن مشتری استفاده می‌شود'),
                    \Filament\Forms\Components\TextInput::make('project_title')
                        ->label('عنوان پروژه')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $user = \App\Models\User::firstOrCreate(
                        ['phone' => $data['client_phone']],
                        [
                            'name' => $data['client_name'] ?? 'مشتری',
                            'role' => 'client',
                            'password' => bcrypt(str()->random(10)),
                        ]
                    );

                    $template = \App\Models\BriefTemplate::find($data['brief_template_id']);

                    $project = \App\Models\Project::create([
                        'client_id' => $user->id,
                        'title' => $data['project_title'],
                        'status' => 'draft',
                        'brief_schema' => $template?->schema ?? [],
                    ]);

                    $token = str()->random(40);
                    $user->update([
                        'magic_link_token' => $token,
                        'magic_link_expires_at' => now()->addDays(2),
                    ]);

                    $link = url("/login/magic/{$token}?redirect_to=/client/complete-brief");
                    $message = "جهت ورود مستقیم به فرم پرسشنامه نیازمندی‌ها، روی لینک زیر کلیک کنید:\n\n{$link}";

                    $user->notify(new \App\Notifications\ProjectNotification($project, 'لینک اختصاصی بریف', $message, 'system'));

                    \Filament\Notifications\Notification::make()
                        ->title('لینک جادویی بریف ارسال شد')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\ProjectsOverviewStatsWidget::class,
        ];
    }
}
