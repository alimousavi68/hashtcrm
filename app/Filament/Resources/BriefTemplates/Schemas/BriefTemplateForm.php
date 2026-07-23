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

    /**
     * فیلد تعیین عنوان گام / دسته‌بندی ویزارد (مشترک در تمام بلوک‌ها)
     */
    private static function stepCategoryInput(): TextInput
    {
        return TextInput::make('step_title')
            ->label('عنوان گام / دسته‌بندی ویزارد (اختیاری)')
            ->placeholder('مثال: گام اول: آشنایی اولیه و دامنه پروژه')
            ->helperText('سوالاتی که عنوان گام یکسان دارند، در ویزارد کارفرما در یک مرحله دسته‌بندی می‌شوند.');
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
                                                ->placeholder('مثال: پرسشنامه جامع و استاندارد طراحی وب‌سایت')
                                                ->required()
                                                ->maxLength(255)
                                                ->helperText('عنوانی مشخص برای شناسایی الگوی پرسشنامه انتخاب کنید.'),
                                            Toggle::make('is_active')
                                                ->label('وضعیت انتشار الگو')
                                                ->default(true)
                                                ->helperText('در صورت فعال بودن، این الگو در هنگام تعریف پروژه‌های جدید قابل انتخاب خواهد بود.'),
                                            Toggle::make('is_default')
                                                ->label('الگوی پیش‌فرض (صفحه /start)')
                                                ->default(false)
                                                ->helperText('در صورت فعال بودن، این پرسشنامه به عنوان پرسشنامه پیش‌فرض در صفحه عمومی ثبت درخواست (/start) استفاده خواهد شد.'),
                                        ]),
                                    ]),

                                Section::make('تنظیمات نمایش فرم به مشتری')
                                    ->description('رفتار کلی فرم پرسشنامه در پورتال مشتری را مشخص کنید.')
                                    ->icon('heroicon-o-presentation-chart-bar')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Toggle::make('wizard_mode')
                                                ->label('حالت ویزاردی دسته‌بندی شده')
                                                ->helperText('در صورت فعال بودن، سوالات بر اساس گام‌ها/دسته‌ها تفکیک شده و به صورت مرحله‌ای نمایش داده می‌شوند.')
                                                ->default(true),
                                        ]),
                                        TextInput::make('guide_notice')
                                            ->label('پیام راهنمای کلی بالای فرم پرسشنامه (برای کارفرما)')
                                            ->placeholder('لطفاً فرم زیر را با دقت تکمیل فرمایید تا فرایند طراحی سریع‌تر آغاز گردد.')
                                            ->helperText('این متن در بالای پورتال مشتری هنگام تکمیل پرسشنامه نمایش داده می‌شود.')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // ─── تب ۲: طراح فیلدها ─────────────────────────────────────────
                        Tabs\Tab::make('builder')
                            ->label('طراح فیلدها و نیازمندی‌ها')
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->schema([
                                Section::make('ساختار فیلدهای پرسشنامه')
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
                                                        static::stepCategoryInput(),
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
                                                        static::stepCategoryInput(),
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

                                                // ─────── ۳. چندانتخابی با چک‌باکس ────────────────────
                                                Block::make('checkboxes')
                                                    ->label(fn (?array $state): string =>
                                                        filled($state['label'] ?? null)
                                                            ? $state['label'] . ' — چندانتخابی (چک‌باکس)'
                                                            : 'چندانتخابی (چک‌باکس)'
                                                    )
                                                    ->icon('heroicon-o-queue-list')
                                                    ->schema([
                                                        Grid::make(2)->schema([
                                                            TextInput::make('name')
                                                                ->label('نام فیلد (شناسه انگلیسی)')
                                                                ->required()
                                                                ->alphaDash()
                                                                ->placeholder('required_features'),
                                                            TextInput::make('label')
                                                                ->label('عنوان فیلد (نمایش به مشتری)')
                                                                ->required()
                                                                ->live(debounce: 500)
                                                                ->placeholder('امکانات کلیدی و فنی مورد نیاز'),
                                                        ]),
                                                        static::stepCategoryInput(),
                                                        TextInput::make('options')
                                                            ->label('گزینه‌ها (با کامای انگلیسی جدا کنید)')
                                                            ->required()
                                                            ->placeholder('فروشگاه آنلاین, درگاه بانکی, وبلاگ, سیستم رزرو')
                                                            ->columnSpanFull(),
                                                        Toggle::make('required')
                                                            ->label('انتخاب حداقل یک مورد اجباری است؟')
                                                            ->default(false),
                                                        static::helpSection(),
                                                    ]),

                                                // ─────── ۴. گزینه‌های تک‌انتخابی ────────────────────
                                                Block::make('radio_choice')
                                                    ->label(fn (?array $state): string =>
                                                        filled($state['label'] ?? null)
                                                            ? $state['label'] . ' — تک‌انتخابی'
                                                            : 'گزینه‌های تک‌انتخابی (رادیویی)'
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
                                                                ->placeholder('آیا دامنه و هاست تهیه کرده‌اید؟'),
                                                        ]),
                                                        static::stepCategoryInput(),
                                                        TextInput::make('options')
                                                            ->label('گزینه‌ها (با کامای انگلیسی جدا کنید)')
                                                            ->required()
                                                            ->placeholder('بله هر دو را دارم, فقط دامنه دارم, هیچکدام را ندارم')
                                                            ->columnSpanFull(),
                                                        Toggle::make('required')
                                                            ->label('پاسخ اجباری است؟')
                                                            ->default(false),
                                                        static::helpSection(),
                                                    ]),

                                                // ─────── ۵. لیست کشویی ───────────────────────────────
                                                Block::make('select')
                                                    ->label(fn (?array $state): string =>
                                                        filled($state['label'] ?? null)
                                                            ? $state['label'] . ' — کشویی'
                                                            : 'لیست کشویی (Select)'
                                                    )
                                                    ->icon('heroicon-o-chevron-down')
                                                    ->schema([
                                                        Grid::make(2)->schema([
                                                            TextInput::make('name')
                                                                ->label('نام فیلد (شناسه انگلیسی)')
                                                                ->required()
                                                                ->alphaDash()
                                                                ->placeholder('launch_timeline'),
                                                            TextInput::make('label')
                                                                ->label('عنوان فیلد (نمایش به مشتری)')
                                                                ->required()
                                                                ->live(debounce: 500)
                                                                ->placeholder('زمان‌بندی مدنظر جهت رونمایی'),
                                                        ]),
                                                        static::stepCategoryInput(),
                                                        TextInput::make('options')
                                                            ->label('گزینه‌ها (با کامای انگلیسی جدا کنید)')
                                                            ->required()
                                                            ->placeholder('اورژانسی (کمتر از ۱ ماه), عادی (۱ تا ۲ ماه), بیش از ۲ ماه')
                                                            ->columnSpanFull(),
                                                        Toggle::make('required')
                                                            ->label('پاسخ اجباری است؟')
                                                            ->default(false),
                                                        static::helpSection(),
                                                    ]),

                                                // ─────── ۶. فهرست پویا (Repeater) ────────────────────
                                                Block::make('repeater')
                                                    ->label(fn (?array $state): string =>
                                                        filled($state['label'] ?? null)
                                                            ? $state['label'] . ' — فهرست پویا (+)'
                                                            : 'فهرست پویا (افزودن آیتم‌های متعدد)'
                                                    )
                                                    ->icon('heroicon-o-list-bullet')
                                                    ->schema([
                                                        Grid::make(2)->schema([
                                                            TextInput::make('name')
                                                                ->label('نام فیلد (شناسه انگلیسی)')
                                                                ->required()
                                                                ->alphaDash()
                                                                ->placeholder('competitors_list'),
                                                            TextInput::make('label')
                                                                ->label('عنوان فیلد (نمایش به مشتری)')
                                                                ->required()
                                                                ->live(debounce: 500)
                                                                ->placeholder('آدرس وب‌سایت رقبای اصلی شما'),
                                                        ]),
                                                        static::stepCategoryInput(),
                                                        Grid::make(2)->schema([
                                                            TextInput::make('placeholder')
                                                                ->label('عنوان آیتم / راهنمای ورودی')
                                                                ->placeholder('آدرس سایت رقیب را وارد کنید...'),
                                                            Toggle::make('required')
                                                                ->label('پاسخ اجباری است؟')
                                                                ->default(false),
                                                        ]),
                                                        static::helpSection(),
                                                    ]),

                                                // ─────── ۷. گروه ورودی‌های متنی (Input Group) ────────
                                                Block::make('input_group')
                                                    ->label(fn (?array $state): string =>
                                                        filled($state['label'] ?? null)
                                                            ? $state['label'] . ' — گروه فیلدها'
                                                            : 'گروه فیلدهای متنی چندگانه'
                                                    )
                                                    ->icon('heroicon-o-rectangle-group')
                                                    ->schema([
                                                        Grid::make(2)->schema([
                                                            TextInput::make('name')
                                                                ->label('نام اصلی (شناسه انگلیسی)')
                                                                ->required()
                                                                ->alphaDash()
                                                                ->placeholder('brand_names'),
                                                            TextInput::make('label')
                                                                ->label('عنوان اصلی گروه')
                                                                ->required()
                                                                ->live(debounce: 500)
                                                                ->placeholder('نام کامل کسب‌وکار / سازمان شما'),
                                                        ]),
                                                        static::stepCategoryInput(),
                                                        TextInput::make('subfields')
                                                            ->label('زیرفیلدها (با کاما جدا کنید)')
                                                            ->required()
                                                            ->placeholder('نام فارسی/بومی, نام انگلیسی')
                                                            ->helperText('برای هر فیلد یک برچسب با کاما جدا کنید.')
                                                            ->columnSpanFull(),
                                                        Toggle::make('required')
                                                            ->label('پاسخ اجباری است؟')
                                                            ->default(false),
                                                        static::helpSection(),
                                                    ]),

                                                // ─────── ۸. ورودی آدرس اینترنتی (URL Input) ──────────
                                                Block::make('url_input')
                                                    ->label(fn (?array $state): string =>
                                                        filled($state['label'] ?? null)
                                                            ? $state['label'] . ' — آدرس URL'
                                                            : 'ورودی لینک / آدرس اینترنتی'
                                                    )
                                                    ->icon('heroicon-o-link')
                                                    ->schema([
                                                        Grid::make(2)->schema([
                                                            TextInput::make('name')
                                                                ->label('نام فیلد (شناسه انگلیسی)')
                                                                ->required()
                                                                ->alphaDash()
                                                                ->placeholder('existing_website'),
                                                            TextInput::make('label')
                                                                ->label('عنوان فیلد (نمایش به مشتری)')
                                                                ->required()
                                                                ->live(debounce: 500)
                                                                ->placeholder('آدرس وب‌سایت فعال یا قدیمی'),
                                                        ]),
                                                        static::stepCategoryInput(),
                                                        Grid::make(2)->schema([
                                                            TextInput::make('placeholder')
                                                                ->label('متن راهنمای درون ورودی')
                                                                ->placeholder('https://example.com'),
                                                            Toggle::make('required')
                                                                ->label('پاسخ اجباری است؟')
                                                                ->default(false),
                                                        ]),
                                                        static::helpSection(),
                                                    ]),

                                                // ─────── ۹. آپلود فایل و مدارک ────────────────────────
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
                                                        static::stepCategoryInput(),
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

                                                // ─────── ۱۰. باکس اطلاعیه و راهنما ───────────────────
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
                                                            ->placeholder('نکات مهم قبل از تکمیل پرسشنامه'),
                                                        static::stepCategoryInput(),
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
                            ->label('راهنمای جدید نمایش و گام‌ها')
                            ->icon('heroicon-o-light-bulb')
                            ->schema([
                                Section::make('نکات استراتژیک دسته‌بندی و طراحی')
                                    ->description('توصیه‌های سیستم جهت ساخت پرسشنامه‌های ویزاردی دسته‌بندی شده')
                                    ->icon('heroicon-o-sparkles')
                                    ->schema([
                                        Text::make('
<p style="margin-bottom:1rem"><strong>📌 دسته‌بندی ویزاردی (گام‌ها):</strong> با تعیین فیلد اختیاری «عنوان گام / دسته‌بندی» در هر سوال، سوالات به صورت خودکار در مراحل ۶‌گانه ویزارد برای مشتری گروه‌بندی می‌شوند.</p>
<p style="margin-bottom:1rem"><strong>📌 بلوک‌های پیشرفته:</strong> می‌توانید از انواع جدید بلوک‌ها مانند <em>چندانتخابی چک‌باکس</em>، <em>گروه ورودی‌های متنی</em>، <em>فهرست پویا (Repeater)</em> و <em>ورودی آدرس لینک (URL)</em> بهره ببرید.</p>
<p><strong>👁️ پیش‌نمایش زنده:</strong> با کلیک روی دکمه «پیش‌نمایش فرم کارفرما» در بالای صفحه، می‌توانید فرم کامل ۶ گام را بر اساس تغییرات خود بررسی کنید.</p>
                                        '),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
