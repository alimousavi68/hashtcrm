<?php

namespace App\Filament\Resources\BriefTemplates\Pages;

use App\Filament\Resources\BriefTemplates\BriefTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditBriefTemplate extends EditRecord
{
    protected static string $resource = BriefTemplateResource::class;

    public function getSubheading(): string|Htmlable|null
    {
        return 'در این صفحه می‌توانید ساختار فیلدها و مشخصات عمومی الگوی بریف را با استفاده از ابزار فیلدساز تغییر دهید.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('toggleActiveStatus')
                ->label(fn (): string => $this->getRecord()->is_active ? 'غیرفعال‌سازی الگو' : 'فعال‌سازی الگو')
                ->icon(fn (): string => $this->getRecord()->is_active ? 'heroicon-o-pause-circle' : 'heroicon-o-play-circle')
                ->color(fn (): string => $this->getRecord()->is_active ? 'warning' : 'success')
                ->action(function () {
                    $record = $this->getRecord();
                    $record->update(['is_active' => !$record->is_active]);

                    Notification::make()
                        ->title('تغییر وضعیت ثبت شد')
                        ->body("الگوی «{$record->name}» " . ($record->is_active ? 'فعال' : 'غیرفعال') . ' گردید.')
                        ->success()
                        ->send();
                }),

            DeleteAction::make()
                ->label('حذف الگو')
                ->modalHeading('حذف الگوی بریف')
                ->modalDescription('آیا از حذف این الگوی بریف اطمینان دارید؟'),
        ];
    }
}
