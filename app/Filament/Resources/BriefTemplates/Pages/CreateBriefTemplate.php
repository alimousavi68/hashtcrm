<?php

namespace App\Filament\Resources\BriefTemplates\Pages;

use App\Filament\Resources\BriefTemplates\BriefTemplateResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateBriefTemplate extends CreateRecord
{
    protected static string $resource = BriefTemplateResource::class;

    public function getSubheading(): string|Htmlable|null
    {
        return 'عنوان الگو را تعیین نموده و فیلدهای مورد نیاز آن را از طریق تب طراح اضافه کنید.';
    }
}
