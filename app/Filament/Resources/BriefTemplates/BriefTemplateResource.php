<?php

namespace App\Filament\Resources\BriefTemplates;

use App\Filament\Clusters\BriefsCluster;
use App\Filament\Resources\BriefTemplates\Pages\CreateBriefTemplate;
use App\Filament\Resources\BriefTemplates\Pages\EditBriefTemplate;
use App\Filament\Resources\BriefTemplates\Pages\ListBriefTemplates;
use App\Filament\Resources\BriefTemplates\Schemas\BriefTemplateForm;
use App\Filament\Resources\BriefTemplates\Tables\BriefTemplatesTable;
use App\Models\BriefTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BriefTemplateResource extends Resource
{
    protected static ?string $model = BriefTemplate::class;

    protected static ?string $cluster = BriefsCluster::class;

    protected static ?string $modelLabel = 'قالب بریف';
    protected static ?string $pluralModelLabel = 'قالب‌های بریف';
    protected static ?string $navigationLabel = 'قالب‌های بریف';
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('is_active', true)->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }

    public static function form(Schema $schema): Schema
    {
        return BriefTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BriefTemplatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBriefTemplates::route('/'),
            'create' => CreateBriefTemplate::route('/create'),
            'edit' => EditBriefTemplate::route('/{record}/edit'),
        ];
    }
}
