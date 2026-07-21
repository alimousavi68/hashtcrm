<?php

namespace App\Filament\Resources\BriefTemplates\Pages;

use App\Filament\Resources\BriefTemplates\BriefTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListBriefTemplates extends ListRecords
{
    protected static string $resource = BriefTemplateResource::class;

    public function getSubheading(): string|Htmlable|null
    {
        return 'مدیریت و تعریف الگوهای استاندارد بریف جهت دریافت دقیق نیازمندی‌های مشتریان';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('تعریف الگوی جدید')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
