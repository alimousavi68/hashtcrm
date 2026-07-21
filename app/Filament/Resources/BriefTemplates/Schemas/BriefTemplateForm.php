<?php

namespace App\Filament\Resources\BriefTemplates\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;

class BriefTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('BriefTemplateTabs')
                    ->tabs([
                        Tabs\Tab::make('general')
                            ->label('مشخصات و تنظیمات الگو')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Section::make('اطلاعات پایه الگو')
                                    ->description('نام و وضعیت انتشار این الگو را جهت استفاده در پروژه‌ها مشخص کنید.')
                                    ->icon('heroicon-o-document-text')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('name')
                                                ->label('نام الگو')
                                                ->placeholder('مثال: قالب بریف اختصاصی طراحی وب‌سایت')
                                                ->required()
                                                ->maxLength(255)
                                                ->helperText('عنوانی مشخص برای شناسایی الگوی بریف انتخاب کنید.'),
                                            Toggle::make('is_active')
                                                ->label('وضعیت انتشار الگو')
                                                ->default(true)
                                                ->helperText('در صورت فعال بودن، این الگو در هنگام تعریف پروژه‌های جدید قابل انتخاب خواهد بود.'),
                                        ]),
                                    ]),
                            ]),

                        Tabs\Tab::make('builder')
                            ->label('طراح فیلدها و نیازمندی‌ها')
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->schema([
                                Section::make('ساختار فیلدهای بریف')
                                    ->description('فیلدها، پرسش‌ها و فایلهای مورد نیاز مشتری را با استفاده از بلوک‌های زیر طراحی کنید.')
                                    ->icon('heroicon-o-swatch')
                                    ->schema([
                                        Builder::make('schema')
                                            ->label('فیلدهای فرم')
                                            ->blocks([
                                                Block::make('text_input')
                                                    ->label('ورودی متن کوتاه (Single Line)')
                                                    ->icon('heroicon-o-bars-3-bottom-left')
                                                    ->schema([
                                                        Grid::make(2)->schema([
                                                            TextInput::make('name')
                                                                ->label('نام فیلد (شناسه انگلیسی سیستم)')
                                                                ->required()
                                                                ->alphaDash()
                                                                ->placeholder('company_name'),
                                                            TextInput::make('label')
                                                                ->label('عنوان فیلد (نمایش به مشتری)')
                                                                ->required()
                                                                ->placeholder('نام برند یا شرکت'),
                                                        ]),
                                                        Grid::make(2)->schema([
                                                            TextInput::make('placeholder')
                                                                ->label('متن راهنمای درون ورودی (Placeholder)')
                                                                ->placeholder('مثال: شرکت هشت...'),
                                                            Toggle::make('required')
                                                                ->label('پاسخ به این فیلد اجباری است؟')
                                                                ->default(false),
                                                        ]),
                                                    ]),

                                                Block::make('textarea')
                                                    ->label('ورودی متن چندخطی (Paragraph)')
                                                    ->icon('heroicon-o-bars-3')
                                                    ->schema([
                                                        Grid::make(2)->schema([
                                                            TextInput::make('name')
                                                                ->label('نام فیلد (شناسه انگلیسی سیستم)')
                                                                ->required()
                                                                ->alphaDash()
                                                                ->placeholder('project_goals'),
                                                            TextInput::make('label')
                                                                ->label('عنوان فیلد (نمایش به مشتری)')
                                                                ->required()
                                                                ->placeholder('اهداف کلیدی پروژه'),
                                                        ]),
                                                        Grid::make(2)->schema([
                                                            TextInput::make('placeholder')
                                                                ->label('متن راهنمای درون ورودی')
                                                                ->placeholder('اهداف و چشم‌اندازهای خود را شرح دهید...'),
                                                            Toggle::make('required')
                                                                ->label('پاسخ به این فیلد اجباری است؟')
                                                                ->default(false),
                                                        ]),
                                                    ]),

                                                Block::make('select')
                                                    ->label('لیست کشویی (Dropdown Select)')
                                                    ->icon('heroicon-o-chevron-down')
                                                    ->schema([
                                                        Grid::make(2)->schema([
                                                            TextInput::make('name')
                                                                ->label('نام فیلد (شناسه انگلیسی)')
                                                                ->required()
                                                                ->alphaDash()
                                                                ->placeholder('business_type'),
                                                            TextInput::make('label')
                                                                ->label('عنوان فیلد (نمایش به مشتری)')
                                                                ->required()
                                                                ->placeholder('نوع کسب‌وکار'),
                                                        ]),
                                                        TextInput::make('options')
                                                            ->label(' گزینه‌ها (با کامای انگلیسی جدا کنید)')
                                                            ->required()
                                                            ->placeholder('فروشی, شرکتی, خدماتی')
                                                            ->helperText('مثال: گزینه ۱, گزینه ۲, گزینه ۳'),
                                                        Toggle::make('required')
                                                            ->label('پاسخ به این فیلد اجباری است؟')
                                                            ->default(false),
                                                    ]),

                                                Block::make('radio_choice')
                                                    ->label('گزینه‌های تک انتخابی (Radio List)')
                                                    ->icon('heroicon-o-check-circle')
                                                    ->schema([
                                                        Grid::make(2)->schema([
                                                            TextInput::make('name')
                                                                ->label('نام فیلد (شناسه انگلیسی)')
                                                                ->required()
                                                                ->alphaDash()
                                                                ->placeholder('has_domain'),
                                                            TextInput::make('label')
                                                                ->label('عنوان فیلد (نمایش به مشتری)')
                                                                ->required()
                                                                ->placeholder('آیا دامنه تهیه کرده‌اید؟'),
                                                        ]),
                                                        TextInput::make('options')
                                                            ->label('گزینه‌ها (با کامای انگلیسی جدا کنید)')
                                                            ->required()
                                                            ->placeholder('بله دارم, خیر نیاز به مشاوره دارم'),
                                                        Toggle::make('required')
                                                            ->label('پاسخ به این فیلد اجباری است؟')
                                                            ->default(false),
                                                    ]),

                                                Block::make('file_upload')
                                                    ->label('آپلود فایل و مدارک (File Upload)')
                                                    ->icon('heroicon-o-arrow-up-tray')
                                                    ->schema([
                                                        Grid::make(2)->schema([
                                                            TextInput::make('name')
                                                                ->label('نام فیلد (شناسه انگلیسی)')
                                                                ->required()
                                                                ->alphaDash()
                                                                ->placeholder('logo_file'),
                                                            TextInput::make('label')
                                                                ->label('عنوان فیلد (نمایش به مشتری)')
                                                                ->required()
                                                                ->placeholder('فایل لوگو با کیفیت بالا'),
                                                        ]),
                                                        Grid::make(2)->schema([
                                                            Toggle::make('required')
                                                                ->label('آپلود اجباری است؟')
                                                                ->default(false),
                                                            Toggle::make('is_essential')
                                                                ->label('ضروری جهت شروع پروژه')
                                                                ->default(false)
                                                                ->helperText('در صورت عدم ارسال، سیستم یادآوری جهت پیگیری کارفرما ارسال می‌کند.'),
                                                        ]),
                                                    ]),

                                                Block::make('instruction_block')
                                                    ->label('باکس راهنما و توضیحات (Notice Box)')
                                                    ->icon('heroicon-o-information-circle')
                                                    ->schema([
                                                        TextInput::make('title')
                                                            ->label('عنوان راهنما')
                                                            ->placeholder('نکات مهم قبل از تکمیل بریف'),
                                                        Textarea::make('content')
                                                            ->label('متن توضیحات راهنما')
                                                            ->rows(3)
                                                            ->placeholder('توضیحات و راهنمایی‌های مورد نیاز را وارد کنید...'),
                                                    ]),
                                            ])
                                            ->collapsible()
                                            ->cloneable()
                                            ->reorderableWithButtons(),
                                    ]),
                            ]),

                        Tabs\Tab::make('guidelines')
                            ->label('راهنمای نمایش و فلو')
                            ->icon('heroicon-o-light-bulb')
                            ->schema([
                                Section::make('نکات استراتژیک طراحی بریف')
                                    ->description('توصیه‌های سیستم جهت دریافت بهترین خروجی نیازمندی‌ها از مشتری')
                                    ->icon('heroicon-o-sparkles')
                                    ->schema([
                                        TextInput::make('guide_notice')
                                            ->label('پیام راهنمای کلی بالای فرم بریف (برای کارفرما)')
                                            ->placeholder('لطفاً فرم زیر را با دقت تکمیل فرمایید تا فرایند طراحی سریع‌تر آغاز گردد.')
                                            ->helperText('این متن در بالای پورتال مشتری هنگام تکمیل بریف نمایش داده می‌شود.'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
