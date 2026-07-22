<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\BriefAnswer;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Pages\EditRecord;

class ManageProjectBrief extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function getNavigationLabel(): string
    {
        return 'پرسشنامه نیازمندی‌ها';
    }

    public function getTitle(): string
    {
        return 'مدیریت و پاسخ‌های پرسشنامه پروژه';
    }

    public function getMaxContentWidth(): \Filament\Support\Enums\Width | string | null
    {
        return \Filament\Support\Enums\Width::Full;
    }

    /**
     * آماده‌سازی مقادیر فرم هنگام لود صفحه
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $project = $this->getRecord();
        $briefAnswer = $project->briefAnswer;

        if ($briefAnswer && is_array($briefAnswer->dynamic_answers)) {
            $data['dynamic_answers'] = $briefAnswer->dynamic_answers;
        } else {
            $data['dynamic_answers'] = [];
        }

        return $data;
    }

    /**
     * ذخیره‌سازی پاسخ‌های ویرایش‌شده توسط ادمین
     */
    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $dynamicAnswers = $data['dynamic_answers'] ?? [];

        BriefAnswer::updateOrCreate(
            ['project_id' => $record->id],
            ['dynamic_answers' => $dynamicAnswers]
        );

        unset($data['dynamic_answers']);

        $record->update($data);

        return $record;
    }

    protected function getHeaderActions(): array
    {
        $project = $this->getRecord();

        return [
            \Filament\Actions\Action::make('export_pdf')
                ->label('خروجی PDF / پرینت')
                ->icon('heroicon-o-printer')
                ->color('info')
                ->url(fn () => route('projects.brief.export-pdf', $project))
                ->openUrlInNewTab(),

            \Filament\Actions\Action::make('export_doc')
                ->label('خروجی Word (DOC)')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->url(fn () => route('projects.brief.export-doc', $project)),
        ];
    }

    public function form(Schema $schema): Schema
    {
        $project = $this->getRecord();
        $schemaBlocks = $project->brief_schema ?? [];
        $existingAnswers = $project->briefAnswer ? ($project->briefAnswer->dynamic_answers ?? []) : [];

        // ────── ۱. محاسبه درصد پیشرفت و آمار پاسخ‌ها ──────
        $totalFields = 0;
        $filledFields = 0;

        foreach ($schemaBlocks as $block) {
            $data = $block['data'] ?? [];
            if (!empty($data['name']) && ($block['type'] ?? '') !== 'instruction_block') {
                $totalFields++;
                $val = $existingAnswers[$data['name']] ?? null;
                if (!empty($val)) {
                    $filledFields++;
                }
            }
        }

        $completionPercent = $totalFields > 0 ? (int)round(($filledFields / $totalFields) * 100) : 0;

        // ────── ۲. ساخت پویای فیلدهای پاسخ به صورت دسته‌بندی‌شده بر اساس گام‌ها ──────
        $categorizedComponents = [];

        if (empty($schemaBlocks)) {
            $categorizedComponents['بدون الگوی فعال'][] = Placeholder::make('no_schema')
                ->label('')
                ->content(new \Illuminate\Support\HtmlString('
                    <div class="p-4 bg-amber-50 text-amber-800 rounded-xl border border-amber-200 text-xs font-medium">
                        هیچ الگوی پرسشنامه‌ای برای این پروژه ست نشده است. می‌توانید از بخش زیر (Schema Builder) سوالات اختصاصی پروژه را تعریف کنید.
                    </div>
                '));
        } else {
            foreach ($schemaBlocks as $block) {
                $type = $block['type'] ?? '';
                $data = $block['data'] ?? [];
                $fieldName = $data['name'] ?? null;
                $label = $data['label'] ?? 'بدون عنوان';
                $stepTitle = !empty($data['step_title']) ? $data['step_title'] : 'گام اول: اطلاعات عمومی و اولیه پروژه';

                if (!$fieldName && $type !== 'instruction_block') continue;

                $component = match ($type) {
                    'text_input', 'url_input' => Forms\Components\TextInput::make("dynamic_answers.{$fieldName}")
                        ->label($label)
                        ->placeholder($data['placeholder'] ?? ''),

                    'textarea' => Forms\Components\Textarea::make("dynamic_answers.{$fieldName}")
                        ->label($label)
                        ->rows(3)
                        ->placeholder($data['placeholder'] ?? ''),

                    'select' => Forms\Components\Select::make("dynamic_answers.{$fieldName}")
                        ->label($label)
                        ->options(empty($data['options']) ? [] : array_combine(
                            array_map('trim', explode(',', $data['options'])),
                            array_map('trim', explode(',', $data['options']))
                        )),

                    'radio_choice' => Forms\Components\Radio::make("dynamic_answers.{$fieldName}")
                        ->label($label)
                        ->options(empty($data['options']) ? [] : array_combine(
                            array_map('trim', explode(',', $data['options'])),
                            array_map('trim', explode(',', $data['options']))
                        )),

                    'checkboxes' => Forms\Components\CheckboxList::make("dynamic_answers.{$fieldName}")
                        ->label($label)
                        ->options(empty($data['options']) ? [] : array_combine(
                            array_map('trim', explode(',', $data['options'])),
                            array_map('trim', explode(',', $data['options']))
                        ))
                        ->columns(2),

                    'input_group' => \Filament\Schemas\Components\Fieldset::make($label)->schema(
                        array_map(function ($sublabel, $idx) use ($fieldName) {
                            return Forms\Components\TextInput::make("dynamic_answers.{$fieldName}_" . ($idx + 1))
                                ->label(trim($sublabel));
                        }, explode(',', $data['subfields'] ?? 'نام فارسی, نام انگلیسی'), array_keys(explode(',', $data['subfields'] ?? 'نام فارسی, نام انگلیسی')))
                    )->columns(2),

                    'repeater' => Forms\Components\Repeater::make("dynamic_answers.{$fieldName}")
                        ->label($label)
                        ->schema([
                            Forms\Components\TextInput::make('value')->label('آیتم')
                        ])
                        ->addActionLabel('+ افزودن آیتم')
                        ->collapsible(),

                    'file_upload' => Forms\Components\FileUpload::make("dynamic_answers.{$fieldName}")
                        ->label($label)
                        ->directory('brief-files'),

                    default => null,
                };

                if ($component) {
                    $categorizedComponents[$stepTitle][] = $component;
                }
            }
        }

        // تبدیل هر گام به یک Section جداگانه و مرتب
        $stepSections = [];
        foreach ($categorizedComponents as $stepTitle => $components) {
            $stepSections[] = Section::make($stepTitle)
                ->icon('heroicon-o-folder-open')
                ->collapsible()
                ->schema([
                    Grid::make(2)->schema($components)
                ]);
        }

        return $schema
            ->schema(array_merge(
                [
                    // ────── ۱. هدر اصلی و نوار پیشرفت مینیمال ──────
                    Section::make('پاسخ‌های ثبت‌شده پرسشنامه پروژه')
                        ->description('اطلاعات تکمیلی و پاسخ‌های ثبت‌شده مشتری بر اساس گام‌های ویزارد بریف')
                        ->icon('heroicon-o-clipboard-document-check')
                        ->columnSpanFull()
                        ->headerActions([
                            \Filament\Actions\Action::make('progress_minimal')
                                ->label('')
                                ->disabled()
                                ->color('gray')
                                ->modalSubmitAction(false)
                                ->extraAttributes(['class' => 'pointer-events-none'])
                                ->view('filament.components.minimal-progress', [
                                    'filledFields' => $filledFields,
                                    'totalFields' => $totalFields,
                                    'completionPercent' => $completionPercent,
                                ])
                        ])
                        ->schema($stepSections)
                ],
                [
                    // ────── ۲. بخش فرم‌ساز اختصاصی پروژه (Schema Builder) ──────
                    Section::make('مدیریت فرم‌ساز پرسشنامه اختصاصی پروژه (Schema Builder)')
                        ->description('در صورت نیاز می‌توانید سوالات جدید به بریف این پروژه اضافه یا ویرایش کنید.')
                        ->icon('heroicon-o-adjustments-vertical')
                        ->collapsible()
                        ->collapsed()
                        ->columnSpanFull()
                        ->schema([
                            Forms\Components\Builder::make('brief_schema')
                                ->label('فیلدهای پویا فرم پرسشنامه')
                                ->blocks([
                                    Forms\Components\Builder\Block::make('text_input')
                                        ->label('ورودی متن کوتاه')
                                        ->icon('heroicon-o-bars-3-bottom-left')
                                        ->schema([
                                            Grid::make(2)->schema([
                                                Forms\Components\TextInput::make('name')->label('شناسه انگلیسی')->required()->alphaDash(),
                                                Forms\Components\TextInput::make('label')->label('عنوان فیلد')->required(),
                                            ]),
                                            Grid::make(2)->schema([
                                                Forms\Components\TextInput::make('placeholder')->label('متن راهنما'),
                                                Forms\Components\Toggle::make('required')->label('اجباری')->default(false),
                                            ]),
                                        ]),
                                    Forms\Components\Builder\Block::make('textarea')
                                        ->label('ورودی متن چندخطی')
                                        ->icon('heroicon-o-bars-3')
                                        ->schema([
                                            Grid::make(2)->schema([
                                                Forms\Components\TextInput::make('name')->label('شناسه انگلیسی')->required()->alphaDash(),
                                                Forms\Components\TextInput::make('label')->label('عنوان فیلد')->required(),
                                            ]),
                                            Grid::make(2)->schema([
                                                Forms\Components\TextInput::make('placeholder')->label('متن راهنما'),
                                                Forms\Components\Toggle::make('required')->label('اجباری')->default(false),
                                            ]),
                                        ]),
                                    Forms\Components\Builder\Block::make('select')
                                        ->label('لیست کشویی')
                                        ->icon('heroicon-o-chevron-down')
                                        ->schema([
                                            Grid::make(2)->schema([
                                                Forms\Components\TextInput::make('name')->label('شناسه انگلیسی')->required()->alphaDash(),
                                                Forms\Components\TextInput::make('label')->label('عنوان فیلد')->required(),
                                            ]),
                                            Forms\Components\TextInput::make('options')->label('گزینه‌ها (با کاما جدا کنید)')->required(),
                                            Forms\Components\Toggle::make('required')->label('اجباری')->default(false),
                                        ]),
                                    Forms\Components\Builder\Block::make('file_upload')
                                        ->label('آپلود فایل')
                                        ->icon('heroicon-o-arrow-up-tray')
                                        ->schema([
                                            Grid::make(2)->schema([
                                                Forms\Components\TextInput::make('name')->label('شناسه انگلیسی')->required()->alphaDash(),
                                                Forms\Components\TextInput::make('label')->label('عنوان فیلد')->required(),
                                            ]),
                                            Forms\Components\Toggle::make('required')->label('اجباری')->default(false),
                                        ]),
                                ])
                                ->collapsible()
                                ->cloneable(),
                        ]),
                ]
            ));
    }
}
