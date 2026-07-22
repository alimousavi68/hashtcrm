<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-adjustments-horizontal';

    public static function getNavigationLabel(): string
    {
        return 'اطلاعات اصلی و وضعیت';
    }

    public function getTitle(): string
    {
        return 'ویرایش اطلاعات اصلی و وضعیت پروژه';
    }

    public function getMaxContentWidth(): \Filament\Support\Enums\Width | string | null
    {
        return \Filament\Support\Enums\Width::Full;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['brief_template_id']) && empty($data['brief_schema'])) {
            $template = \App\Models\BriefTemplate::find($data['brief_template_id']);
            if ($template && !empty($template->schema)) {
                $data['brief_schema'] = $template->schema;
            }
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
