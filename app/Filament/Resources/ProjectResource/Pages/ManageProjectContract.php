<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Resources\Pages\EditRecord;

class ManageProjectContract extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-check';

    public static function getNavigationLabel(): string
    {
        return 'قرارداد و امضاء';
    }

    public function getTitle(): string
    {
        return 'مدیریت قرارداد و امضای دیجیتال پروژه';
    }

    public function getMaxContentWidth(): \Filament\Support\Enums\Width | string | null
    {
        return \Filament\Support\Enums\Width::Full;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('مشخصات و متن قرارداد پروژه')
                    ->description('متن حقوقی قرارداد که توسط مشتری مشاهده و به صورت دیجیتال امضا می‌شود.')
                    ->icon('heroicon-o-document-text')
                    ->relationship('contract')
                    ->schema([
                        Grid::make(3)->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('عنوان قرارداد')
                                ->required()
                                ->default('قرارداد همکاری طراحی و توسعه سایت')
                                ->maxLength(255)
                                ->columnSpan(2),
                            Forms\Components\DateTimePicker::make('signed_at')
                                ->label('تاریخ ثبت امضا')
                                ->disabled()
                                ->columnSpan(1),
                        ]),
                        Forms\Components\RichEditor::make('content')
                            ->label('متن کامل قرارداد')
                            ->required()
                            ->placeholder('متن قرارداد را وارد کنید...')
                            ->columnSpanFull(),
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('signature_name')
                                ->label('نام و نام خانوادگی امضاکننده')
                                ->disabled(),
                            Forms\Components\TextInput::make('signature_national_code')
                                ->label('کد ملی امضاکننده')
                                ->disabled(),
                        ]),
                    ]),
            ]);
    }
}
