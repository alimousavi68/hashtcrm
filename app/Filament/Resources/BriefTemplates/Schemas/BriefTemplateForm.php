<?php

namespace App\Filament\Resources\BriefTemplates\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;

class BriefTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('مشخصات الگو')
                    ->schema([
                        TextInput::make('name')
                            ->label('نام الگو')
                            ->required()
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label('فعال')
                            ->default(true),
                    ])->columns(2),

                Section::make('طراحی فیلدها')
                    ->schema([
                        Builder::make('schema')
                            ->label('فیلدهای فرم')
                            ->blocks([
                                Block::make('text_input')
                                    ->label('ورودی متن کوتاه')
                                    ->icon('heroicon-o-bars-3-bottom-left')
                                    ->schema([
                                        TextInput::make('name')->label('نام فیلد (انگلیسی - برای سیستم)')->required()->alphaDash(),
                                        TextInput::make('label')->label('عنوان (نمایش به مشتری)')->required(),
                                        TextInput::make('placeholder')->label('متن راهنما (Placeholder)'),
                                        Toggle::make('required')->label('اجباری است؟')->default(false),
                                    ]),
                                Block::make('textarea')
                                    ->label('ورودی متن چندخطی')
                                    ->icon('heroicon-o-bars-3')
                                    ->schema([
                                        TextInput::make('name')->label('نام فیلد (انگلیسی - برای سیستم)')->required()->alphaDash(),
                                        TextInput::make('label')->label('عنوان (نمایش به مشتری)')->required(),
                                        TextInput::make('placeholder')->label('متن راهنما (Placeholder)'),
                                        Toggle::make('required')->label('اجباری است؟')->default(false),
                                    ]),
                                Block::make('select')
                                    ->label('لیست کشویی (Select)')
                                    ->icon('heroicon-o-chevron-down')
                                    ->schema([
                                        TextInput::make('name')->label('نام فیلد (انگلیسی - برای سیستم)')->required()->alphaDash(),
                                        TextInput::make('label')->label('عنوان (نمایش به مشتری)')->required(),
                                        TextInput::make('options')->label('گزینه‌ها (با کاما جدا کنید)')->required()->helperText('مثال: گزینه ۱, گزینه ۲, گزینه ۳'),
                                        Toggle::make('required')->label('اجباری است؟')->default(false),
                                    ]),
                                Block::make('file_upload')
                                    ->label('آپلود فایل')
                                    ->icon('heroicon-o-arrow-up-tray')
                                    ->schema([
                                        TextInput::make('name')->label('نام فیلد (انگلیسی - برای سیستم)')->required()->alphaDash(),
                                        TextInput::make('label')->label('عنوان (نمایش به مشتری)')->required(),
                                        Toggle::make('required')->label('اجباری است؟')->default(false),
                                        Toggle::make('is_essential')->label('برای شروع پروژه الزامی است؟')->default(false)->helperText('در صورت فعال نبودن، بعدا سیستم یادآوری ارسال می‌کند.'),
                                    ]),
                            ])
                            ->collapsible()
                            ->cloneable(),
                    ]),
            ]);
    }
}
