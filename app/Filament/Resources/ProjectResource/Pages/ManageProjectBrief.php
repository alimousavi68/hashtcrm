<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Resources\Pages\EditRecord;

class ManageProjectBrief extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-list';


    public static function getNavigationLabel(): string
    {
        return 'بریف نیازمندی‌ها';
    }

    public function getTitle(): string
    {
        return 'مدیریت و پاسخ‌های بریف پروژه';
    }

    public function getMaxContentWidth(): \Filament\Support\Enums\Width | string | null
    {
        return \Filament\Support\Enums\Width::Full;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('پاسخ‌های ثبت‌شده بریف توسط مشتری')
                    ->description('اطلاعات تکمیل‌شده توسط مشتری در ویزارد بریف')
                    ->icon('heroicon-o-user-circle')
                    ->collapsible()
                    ->relationship('briefAnswer')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('business_name')
                                ->label('نام برند / کسب‌وکار')
                                ->disabled(),
                            Forms\Components\TextInput::make('design_style')
                                ->label('سبک طراحی مورد نظر')
                                ->disabled(),
                            Forms\Components\TextInput::make('color_preferences')
                                ->label('رنگ‌بندی‌های پیشنهادی')
                                ->disabled(),
                            Forms\Components\TextInput::make('competitors')
                                ->label('رقیبان اصلی')
                                ->disabled(),
                        ]),
                        Forms\Components\Textarea::make('business_description')
                            ->label('توضیحات و حوزه فعالیت')
                            ->rows(3)
                            ->disabled(),
                        Forms\Components\Textarea::make('target_audience')
                            ->label('مخاطبان هدف')
                            ->rows(3)
                            ->disabled(),
                        Forms\Components\Textarea::make('extra_notes')
                            ->label('توضیحات و نکات تکمیلی')
                            ->rows(3)
                            ->disabled(),
                    ]),

                Section::make('مدیریت فرم‌ساز بریف اختصاصی (Schema Builder)')
                    ->description('در این بخش می‌توانید سوالات و فیلدهای بریف اختصاصی این پروژه را تعیین یا تغییر دهید.')
                    ->icon('heroicon-o-adjustments-vertical')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Builder::make('brief_schema')
                            ->label('فیلدهای پویا فرم بریف')
                            ->blocks([
                                Forms\Components\Builder\Block::make('text_input')
                                    ->label('ورودی متن کوتاه')
                                    ->icon('heroicon-o-bars-3-bottom-left')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')->label('نام فیلد (انگلیسی)')->required()->alphaDash(),
                                        Forms\Components\TextInput::make('label')->label('عنوان فیلد')->required(),
                                        Forms\Components\TextInput::make('placeholder')->label('متن راهنما'),
                                        Forms\Components\Toggle::make('required')->label('اجباری است؟')->default(false),
                                    ]),
                                Forms\Components\Builder\Block::make('textarea')
                                    ->label('ورودی متن چندخطی')
                                    ->icon('heroicon-o-bars-3')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')->label('نام فیلد (انگلیسی)')->required()->alphaDash(),
                                        Forms\Components\TextInput::make('label')->label('عنوان فیلد')->required(),
                                        Forms\Components\TextInput::make('placeholder')->label('متن راهنما'),
                                        Forms\Components\Toggle::make('required')->label('اجباری است؟')->default(false),
                                    ]),
                                Forms\Components\Builder\Block::make('select')
                                    ->label('لیست کشویی')
                                    ->icon('heroicon-o-chevron-down')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')->label('نام فیلد (انگلیسی)')->required()->alphaDash(),
                                        Forms\Components\TextInput::make('label')->label('عنوان فیلد')->required(),
                                        Forms\Components\TextInput::make('options')->label('گزینه‌ها (با کاما جدا کنید)')->required(),
                                        Forms\Components\Toggle::make('required')->label('اجباری است؟')->default(false),
                                    ]),
                                Forms\Components\Builder\Block::make('file_upload')
                                    ->label('آپلود فایل')
                                    ->icon('heroicon-o-arrow-up-tray')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')->label('نام فیلد (انگلیسی)')->required()->alphaDash(),
                                        Forms\Components\TextInput::make('label')->label('عنوان فیلد')->required(),
                                        Forms\Components\Toggle::make('required')->label('اجباری است؟')->default(false),
                                        Forms\Components\Toggle::make('is_essential')->label('برای شروع الزامی است؟')->default(false),
                                    ]),
                            ])
                            ->collapsible()
                            ->cloneable(),
                    ]),
            ]);
    }
}
