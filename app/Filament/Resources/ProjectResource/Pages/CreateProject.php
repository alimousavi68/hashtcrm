<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!empty($data['brief_template_id']) && empty($data['brief_schema'])) {
            $template = \App\Models\BriefTemplate::find($data['brief_template_id']);
            if ($template && !empty($template->schema)) {
                $data['brief_schema'] = $template->schema;
            }
        }

        return $data;
    }
}
