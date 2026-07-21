<?php

namespace App\Filament\Resources\BriefTemplates\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Text;

class BriefTemplateForm
{
    /**
     * اسامی کوتاه نوع بلوک برای نمایش در هدر آیتم Builder
     */
    private static array $blockTypeLabels = [
        'text_input'        => 'متن کوتاه',
        'textarea'          => 'متن بلند',
        'select'            => 'انتخابی',
        'radio_choice'      => 'تک‌انتخابی',
        'file_upload'       => 'فایل',
        'instruction_block' => 'راهنما',
    ];

    /**
     * ساخت بخش راهنمای غنی (مشترک بین همه بلوک‌ها)
     */
    private static function helpSection(): Section
    {
        return Section::make('📖 راهنمای بلوک (اختیاری)')
            ->description('می‌توانید توضیحات، تصویر، لینک یا لیست مرتب برای راهنمایی مشتری در تکمیل این فیلد اضافه کنید.')
            ->collapsible()
            ->collapsed()
            ->schema([
                RichEditor::make('help_content')
                    ->label('محتوای راهنما')
                    ->toolbarButtons([
                        'h2',
                        'h3',
                        'bold',
                        'italic',
                        'underline',
                        'bulletList',
                        'orderedList',
                        'link',
                        'blockquote',
                        'attachFiles',
                    ])
                    ->placeholder('توضیحات، راهنما یا مثال‌های کمکی برای مشتری را وارد کنید...')
                    ->columnSpanFull(),
                Grid::make(2)->schema([
                    Toggle::make('help_default_open')
                        ->label('راهنما پیش‌فرض باز باشد؟')
                        ->helperText('اگر فعال باشد، هنگامی که مشتری فرم را باز می‌کند این باکس راهنما از ابتدا باز و قابل مشاهده است.')
                        ->default(false),
                ]),
            ]);
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('BriefTemplateTabs')
                    ->tabs([

                        // ─── تب ۱: مشخصات الگو ─────────────────────────────────────────
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

                                Section::make('تنظیمات نمایش فرم به مشتری')
                                    ->description('رفتار کلی فرم بریف در پورتال مشتری را مشخص کنید.')
                                    ->icon('heroicon-o-presentation-chart-bar')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Toggle::make('wizard_mode')
                                                ->label('حالت ویزاردی (یک سوال در هر صفحه)')
                                                ->helperText('در صورت فعال بودن، مشتری فیلدها را به صورت مرحله‌ای و یکی‌یکی مشاهده و تکمیل می‌کند.')
                                                ->default(false),
                                        ]),
                                        TextInput::make('guide_notice')
                                            ->label('پیام راهنمای کلی بالای فرم بریف (برای کارفرما)')
                                            ->placeholder('لطفاً فرم زیر را با دقت تکمیل فرمایید تا فرایند طراحی سریع‌تر آغاز گردد.')
                                            ->helperText('این متن در بالای پورتال مشتری هنگام تکمیل بریف نمایش داده می‌شود.')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // ─── تب ۲: طراح فیلدها ─────────────────────────────────────────
                        Tabs\Tab::make('builder')
                            ->label('طراح فیلدها و نیازمندی‌ها')
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->schema([
                                Section::make('ساختار فیلدهای بریف')
                                    ->description('فیلدها، پرسش‌ها و فایل‌های مورد نیاز مشتری را با استفاده از بلوک‌های زیر طراحی کنید.')
                                    ->icon('heroicon-o-swatch')
                                    ->schema([
                                        Builder::make('schema')
                                            ->label('فیلدهای فرم')
                                            ->blockNumbers(true)
                                            ->blockLabels(true)
                                            ->blocks([

                                                // ─────── ۱. ورودی متن کوتاه ───────────────────────────
                                                Block::make('text_input')
                                                    ->label(fn (?array $state): string =>
                                                        filled($state['label'] ?? null)
                                                            ? $state['label'] . ' — متن کوتاه'
                                                            : 'ورودی متن کوتاه'
                                                    )
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
                                                                ->live(debounce: 500)
                                                                ->placeholder('نام برند یا شرکت'),
                                                        ]),
                                                        Grid::make(2)->schema([
                                                            TextInput::make('placeholder')
                                                                ->label('متن راهنمای درون ورودی')
                                                                ->placeholder('مثال: شرکت هشت...'),
                                                            Toggle::make('required')
                                                                ->label('پاسخ اجباری است؟')
                                                                ->default(false),
                                                        ]),
                                                        static::helpSection(),
                                                    ]),

                                                // ─────── ۲. ورودی متن چندخطی ─────────────────────────
                                                Block::make('textarea')
                                                    ->label(fn (?array $state): string =>
                                                        filled($state['label'] ?? null)
                                                            ? $state['label'] . ' — متن بلند'
                                                            : 'ورودی متن چندخطی'
                                                    )
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
                                                                ->live(debounce: 500)
                                                                ->placeholder('اهداف کلیدی پروژه'),
                                                        ]),
                                                        Grid::make(2)->schema([
                                                            TextInput::make('placeholder')
                                                                ->label('متن راهنمای درون ورودی')
                                                                ->placeholder('اهداف و چشم‌اندازهای خود را شرح دهید...'),
                                                            Toggle::make('required')
                                                                ->label('پاسخ اجباری است؟')
                                                                ->default(false),
                                                        ]),
                                                        static::helpSection(),
                                                    ]),

                                                // ─────── ۳. لیست کشویی ───────────────────────────────
                                                Block::make('select')
                                                    ->label(fn (?array $state): string =>
                                                        filled($state['label'] ?? null)
                                                            ? $state['label'] . ' — انتخابی'
                                                            : 'لیست کشویی'
                                                    )
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
                                                                ->live(debounce: 500)
                                                                ->placeholder('نوع کسب‌وکار'),
                                                        ]),
                                                        TextInput::make('options')
                                                            ->label('گزینه‌ها (با کامای انگلیسی جدا کنید)')
                                                            ->required()
                                                            ->placeholder('فروشی, شرکتی, خدماتی')
                                                            ->helperText('مثال: گزینه ۱, گزینه ۲, گزینه ۳')
                                                            ->columnSpanFull(),
                                                        Toggle::make('required')
                                                            ->label('پاسخ اجباری است؟')
                                                            ->default(false),
                                                        static::helpSection(),
                                                    ]),

                                                // ─────── ۴. گزینه‌های تک‌انتخابی ────────────────────
                                                Block::make('radio_choice')
                                                    ->label(fn (?array $state): string =>
                                                        filled($state['label'] ?? null)
                                                            ? $state['label'] . ' — تک‌انتخابی'
                                                            : 'گزینه‌های تک‌انتخابی'
                                                    )
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
                                                                ->live(debounce: 500)
                                                                ->placeholder('آیا دامنه تهیه کرده‌اید؟'),
                                                        ]),
                                                        TextInput::make('options')
                                                            ->label('گزینه‌ها (با کامای انگلیسی جدا کنید)')
                                                            ->required()
                                                            ->placeholder('بله دارم, خیر نیاز به مشاوره دارم')
                                                            ->columnSpanFull(),
                                                        Toggle::make('required')
                                                            ->label('پاسخ اجباری است؟')
                                                            ->default(false),
                                                        static::helpSection(),
                                                    ]),

                                                // ─────── ۵. آپلود فایل ───────────────────────────────
                                                Block::make('file_upload')
                                                    ->label(fn (?array $state): string =>
                                                        filled($state['label'] ?? null)
                                                            ? $state['label'] . ' — آپلود فایل'
                                                            : 'آپلود فایل و مدارک'
                                                    )
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
                                                                ->live(debounce: 500)
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
                                                        static::helpSection(),
                                                    ]),

                                                // ─────── ۶. باکس اطلاعیه و راهنما ───────────────────
                                                Block::make('instruction_block')
                                                    ->label(fn (?array $state): string =>
                                                        filled($state['title'] ?? null)
                                                            ? $state['title'] . ' — اطلاعیه'
                                                            : 'باکس اطلاعیه و توضیحات'
                                                    )
                                                    ->icon('heroicon-o-information-circle')
                                                    ->schema([
                                                        TextInput::make('title')
                                                            ->label('عنوان اطلاعیه')
                                                            ->live(debounce: 500)
                                                            ->placeholder('نکات مهم قبل از تکمیل بریف'),
                                                        RichEditor::make('content')
                                                            ->label('متن اطلاعیه (قابل نمایش به مشتری)')
                                                            ->toolbarButtons([
                                                                'h2',
                                                                'h3',
                                                                'bold',
                                                                'italic',
                                                                'underline',
                                                                'bulletList',
                                                                'orderedList',
                                                                'link',
                                                                'blockquote',
                                                                'attachFiles',
                                                            ])
                                                            ->placeholder('متن اطلاعیه کامل را اینجا وارد کنید...')
                                                            ->columnSpanFull(),
                                                    ]),

                                            ])
                                            ->collapsible()
                                            ->cloneable()
                                            ->reorderableWithButtons(),
                                    ]),
                            ]),

                        // ─── تب ۳: راهنمای نمایش ───────────────────────────────────────
                        Tabs\Tab::make('guidelines')
                            ->label('راهنمای نمایش و فلو')
                            ->icon('heroicon-o-light-bulb')
                            ->schema([
                                Section::make('نکات استراتژیک طراحی بریف')
                                    ->description('توصیه‌های سیستم جهت دریافت بهترین خروجی نیازمندی‌ها از مشتری')
                                    ->icon('heroicon-o-sparkles')
                                    ->schema([
                                        Text::make('
<p style="margin-bottom:1rem"><strong>👁️ پیش‌نمایش زنده:</strong> با کلیک روی دکمه «پیش‌نمایش فرم کارفرما» در بالای صفحه یا جدول الگوها، می‌توانید دقیقاً ظاهر و ساختاری که مشتری در پورتال مشاهده خواهد کرد را به همراه راهنماها و مدارک ضروری بررسی کنید.</p>
<p style="margin-bottom:1rem"><strong>📌 حالت ویزاردی:</strong> با فعال‌سازی گزینه «یک سوال در هر صفحه» در تب مشخصات، مشتری در هر مرحله فقط یک فیلد را می‌بیند. این حالت برای بریف‌های طولانی با ۷+ فیلد توصیه می‌شود.</p>
<p style="margin-bottom:1rem"><strong>📌 باکس راهنما:</strong> برای هر فیلد می‌توانید یک بخش راهنمای غنی (متن، تصویر، لیست) اضافه کنید. تعیین کنید که آیا این راهنما از ابتدا باز باشد یا مشتری در صورت نیاز آن را باز کند.</p>
<p><strong>📌 شماره‌گذاری:</strong> فیلدها به صورت خودکار شماره‌گذاری می‌شوند و عنوان هر فیلد در هدر آن نمایش داده می‌شود.</p>
                                        '),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
