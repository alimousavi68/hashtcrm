<?php

namespace App\Filament\Resources\BriefTemplates\Pages;

use App\Filament\Resources\BriefTemplates\BriefTemplateResource;
use App\Models\BriefTemplate;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListBriefTemplates extends ListRecords
{
    protected static string $resource = BriefTemplateResource::class;

    protected string $view = 'filament.admin.pages.list-brief-templates';

    public string $search = '';
    public string $sortOrder = 'default';

    public function getSubheading(): string|Htmlable|null
    {
        return 'مدیریت و تعریف پرسشنامه‌های استاندارد جهت دریافت دقیق نیازمندی‌های مشتریان';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('ساخت پرسشنامه جدید')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    public function getTemplatesProperty()
    {
        $query = BriefTemplate::query();

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        match ($this->sortOrder) {
            'newest' => $query->latest(),
            'most_views' => $query->orderBy('views_count', 'desc'),
            'most_responses' => $query->orderBy('responses_count', 'desc'),
            default => $query->orderBy('id', 'asc'),
        };

        return $query->get();
    }

    public function toggleActive(int $id): void
    {
        $template = BriefTemplate::find($id);
        if (!$template) return;

        $template->update(['is_active' => !$template->is_active]);

        Notification::make()
            ->title('تغییر وضعیت انجام شد')
            ->body("وضعیت پرسشنامه «{$template->name}» " . ($template->is_active ? 'فعال' : 'غیرفعال') . ' گردید.')
            ->success()
            ->send();
    }

    public function deleteTemplate(int $id): void
    {
        $template = BriefTemplate::find($id);
        if (!$template) return;

        $name = $template->name;
        $template->delete();

        Notification::make()
            ->title('حذف موفقیت‌آمیز')
            ->body("پرسشنامه «{$name}» با موفقیت حذف گردید.")
            ->success()
            ->send();
    }

    public function previewTemplate(int $id): void
    {
        $template = BriefTemplate::find($id);
        if ($template) {
            $template->increment('views_count');
        }

        $this->mountAction('previewModal', ['record' => $id]);
    }

    public function previewModalAction(): Action
    {
        return Action::make('previewModal')
            ->label('پیش‌نمایش فرم پرسشنامه')
            ->modalHeading(fn (array $arguments): string => "پیش‌نمایش نحوه نمایش فرم: «" . (BriefTemplate::find($arguments['record'] ?? null)?->name ?? '') . "»")
            ->modalWidth('3xl')
            ->modalContent(fn (array $arguments): \Illuminate\Contracts\View\View => view('filament.admin.brief-template-preview', [
                'record' => BriefTemplate::find($arguments['record'] ?? null)
            ]))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('بستن پیش‌نمایش');
    }
}
