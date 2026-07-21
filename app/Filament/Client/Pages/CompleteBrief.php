<?php

namespace App\Filament\Client\Pages;

use Filament\Pages\Page;
use App\Models\Project;
use App\Models\BriefAnswer;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;

class CompleteBrief extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected string $view = 'filament.client.pages.complete-brief';
    protected ?string $heading = 'تکمیل پرسشنامه نیازمندی‌های پروژه';
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];
    public ?Project $project = null;

    public function getLayout(): string
    {
        return 'filament-panels::components.layout.base';
    }

    public function mount(): void
    {
        $this->project = Project::where('client_id', Auth::guard('client')->id())
            ->where('status', 'brief')
            ->first();

        if (!$this->project) {
            redirect()->to(Projects::getUrl());
            return;
        }

        if (!$this->project->brief_schema || count($this->project->brief_schema) === 0) {
            Notification::make()->title('خطا')->body('پرسشنامه‌ای برای این پروژه تنظیم نشده است. لطفا با پشتیبانی تماس بگیرید.')->danger()->send();
            return;
        }

        $existingAnswers = $this->project->briefAnswer ? $this->project->briefAnswer->dynamic_answers : [];
        $this->form->fill($existingAnswers ?: []);
    }

    public function form(Schema $schema): Schema
    {
        $schemaBlocks = $this->project ? ($this->project->brief_schema ?? []) : [];
        $activeTemplate = \App\Models\BriefTemplate::where('is_active', true)->first();
        $isWizardMode = $activeTemplate ? $activeTemplate->wizard_mode : true;

        $categorizedFields = [];

        foreach ($schemaBlocks as $index => $block) {
            $type = $block['type'] ?? '';
            $data = $block['data'] ?? [];

            if (empty($data['name']) && $type !== 'instruction_block') continue;

            $stepTitle = !empty($data['step_title']) ? $data['step_title'] : 'گام اول: اطلاعات عمومی و اولیه پروژه';

            $field = match ($type) {
                'text_input' => TextInput::make($data['name'])
                    ->label($data['label'] ?? 'بدون عنوان')
                    ->placeholder($data['placeholder'] ?? ''),

                'url_input' => TextInput::make($data['name'])
                    ->label($data['label'] ?? 'بدون عنوان')
                    ->placeholder($data['placeholder'] ?? 'https://example.com')
                    ->prefix('https://')
                    ->url(),

                'textarea' => Textarea::make($data['name'])
                    ->label($data['label'] ?? 'بدون عنوان')
                    ->rows(3)
                    ->placeholder($data['placeholder'] ?? ''),

                'select' => Select::make($data['name'])
                    ->label($data['label'] ?? 'بدون عنوان')
                    ->options(empty($data['options']) ? [] : array_combine(
                        array_map('trim', explode(',', $data['options'])),
                        array_map('trim', explode(',', $data['options']))
                    )),

                'radio_choice' => Radio::make($data['name'])
                    ->label($data['label'] ?? 'بدون عنوان')
                    ->options(empty($data['options']) ? [] : array_combine(
                        array_map('trim', explode(',', $data['options'])),
                        array_map('trim', explode(',', $data['options']))
                    )),

                'checkboxes' => CheckboxList::make($data['name'])
                    ->label($data['label'] ?? 'بدون عنوان')
                    ->options(empty($data['options']) ? [] : array_combine(
                        array_map('trim', explode(',', $data['options'])),
                        array_map('trim', explode(',', $data['options']))
                    ))
                    ->columns(2),

                'repeater' => Repeater::make($data['name'])
                    ->label($data['label'] ?? 'فهرست پویا')
                    ->schema([
                        TextInput::make('value')
                            ->label('آیتم')
                            ->placeholder($data['placeholder'] ?? 'آدرس سایت یا توضیح...')
                    ])
                    ->addActionLabel('+ افزودن آیتم جدید')
                    ->collapsible(),

                'input_group' => Grid::make(2)->schema(
                    array_map(function ($sublabel, $idx) use ($data) {
                        return TextInput::make($data['name'] . '_' . ($idx + 1))
                            ->label(trim($sublabel));
                    }, explode(',', $data['subfields'] ?? 'نام فارسی, نام انگلیسی'), array_keys(explode(',', $data['subfields'] ?? 'نام فارسی, نام انگلیسی')))
                ),

                'file_upload' => FileUpload::make($data['name'])
                    ->label($data['label'] ?? 'بدون عنوان'),

                default => null,
            };

            if ($field) {
                if (!empty($data['required'])) {
                    $field->required();
                }

                $categorizedFields[$stepTitle][] = $field;
            }
        }

        $steps = [];

        if ($isWizardMode && count($categorizedFields) > 0) {
            foreach ($categorizedFields as $stepTitle => $fields) {
                $steps[] = Wizard\Step::make($stepTitle)
                    ->label($stepTitle)
                    ->schema($fields)
                    ->icon('heroicon-o-clipboard-document-check');
            }
        } else {
            $allFields = [];
            foreach ($categorizedFields as $fields) {
                $allFields = array_merge($allFields, $fields);
            }
            $steps[] = Wizard\Step::make('تکمیل پرسشنامه نیازمندی‌ها')
                ->schema($allFields)
                ->icon('heroicon-o-document-text');
        }

        return $schema
            ->schema([
                Wizard::make($steps)
                    ->submitAction(new HtmlString('
                        <button type="submit" class="fi-btn fi-btn-color-primary fi-color-primary fi-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 disabled:pointer-events-none disabled:opacity-70 bg-primary-600 hover:bg-primary-500 text-white shadow-sm fi-ac-btn-action px-6 py-3 rounded-xl text-sm">
                            ثبت نهایی اطلاعات پرسشنامه
                        </button>
                    '))
            ])
            ->statePath('data');
    }

    public function submitForm(): void
    {
        $state = $this->form->getState();

        if (!$this->project) return;

        BriefAnswer::updateOrCreate(
            ['project_id' => $this->project->id],
            ['dynamic_answers' => $state]
        );

        $this->project->update([
            'status' => 'contract'
        ]);

        Notification::make()
            ->title('اطلاعات با موفقیت ثبت شد')
            ->body('پروژه شما وارد مرحله قرارداد و امور مالی شد.')
            ->success()
            ->send();

        redirect()->to(Projects::getUrl());
    }
}
