<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationLabel = 'پروژه‌ها';
    protected static ?string $pluralModelLabel = 'پروژه‌ها';
    protected static ?string $modelLabel = 'پروژه';
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-folder-open';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('اطلاعات اصلی پروژه')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('عنوان پروژه')
                                ->required(),
                            Forms\Components\Select::make('client_id')
                                ->label('مشتری')
                                ->relationship(
                                    name: 'client',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: fn (Builder $query) => $query->where('role', 'client')
                                )
                                ->searchable()
                                ->preload()
                                ->required(),
                            Forms\Components\Select::make('status')
                                ->label('وضعیت پروژه')
                                ->options([
                                    'draft' => 'پیش‌نویس اولیه',
                                    'brief' => 'تکمیل بریف نیازمندی‌ها',
                                    'contract' => 'امضای قرارداد و امور مالی',
                                    'in_progress' => 'در حال طراحی و توسعه',
                                    'review' => 'بازنگری و ثبت نظرات (دمو)',
                                    'ready_handover' => 'آماده‌سازی بسته تحویل',
                                    'completed' => 'تحویل نهایی و خاتمه',
                                ])
                                ->default('draft')
                                ->required(),
                            Forms\Components\Toggle::make('is_settled')
                                ->label('تسویه حساب کامل مالی')
                                ->default(false)
                                ->required(),
                            Forms\Components\DateTimePicker::make('feedback_deadline')
                                ->label('مهلت ارسال بازنگری و فیدبک')
                                ->nullable(),
                            Forms\Components\TextInput::make('demo_url')
                                ->label('لینک دمو (پیش‌نمایش پروژه)')
                                ->url()
                                ->nullable(),
                        ])
                    ]),

                Section::make('مدیریت بریف اختصاصی این پروژه')
                    ->description('در این قسمت می‌توانید فیلدهای بریف را برای این پروژه تعیین یا حذف کنید. مشتری در اولین ورود ملزم به تکمیل این فرم است.')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Forms\Components\Builder::make('brief_schema')
                            ->label('فیلدهای فرم بریف')
                            ->blocks([
                                Forms\Components\Builder\Block::make('text_input')
                                    ->label('ورودی متن کوتاه')
                                    ->icon('heroicon-o-bars-3-bottom-left')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')->label('نام فیلد (انگلیسی)')->required()->alphaDash(),
                                        Forms\Components\TextInput::make('label')->label('عنوان')->required(),
                                        Forms\Components\TextInput::make('placeholder')->label('متن راهنما'),
                                        Forms\Components\Toggle::make('required')->label('اجباری است؟')->default(false),
                                    ]),
                                Forms\Components\Builder\Block::make('textarea')
                                    ->label('ورودی متن چندخطی')
                                    ->icon('heroicon-o-bars-3')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')->label('نام فیلد (انگلیسی)')->required()->alphaDash(),
                                        Forms\Components\TextInput::make('label')->label('عنوان')->required(),
                                        Forms\Components\TextInput::make('placeholder')->label('متن راهنما'),
                                        Forms\Components\Toggle::make('required')->label('اجباری است؟')->default(false),
                                    ]),
                                Forms\Components\Builder\Block::make('select')
                                    ->label('لیست کشویی')
                                    ->icon('heroicon-o-chevron-down')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')->label('نام فیلد (انگلیسی)')->required()->alphaDash(),
                                        Forms\Components\TextInput::make('label')->label('عنوان')->required(),
                                        Forms\Components\TextInput::make('options')->label('گزینه‌ها (با کاما جدا کنید)')->required(),
                                        Forms\Components\Toggle::make('required')->label('اجباری است؟')->default(false),
                                    ]),
                                Forms\Components\Builder\Block::make('file_upload')
                                    ->label('آپلود فایل')
                                    ->icon('heroicon-o-arrow-up-tray')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')->label('نام فیلد (انگلیسی)')->required()->alphaDash(),
                                        Forms\Components\TextInput::make('label')->label('عنوان')->required(),
                                        Forms\Components\Toggle::make('required')->label('اجباری است؟')->default(false),
                                        Forms\Components\Toggle::make('is_essential')->label('برای شروع الزامی است؟')->default(false),
                                    ]),
                            ])
                            ->collapsible()
                            ->cloneable(),
                    ]),

                Section::make('اطلاعات دسترسی و دارایی‌ها')
                    ->description('اطلاعات محرمانه و دسترسی‌های ثبت‌شده توسط مشتری (رمزنگاری شده در دیتابیس)')
                    ->collapsible()
                    ->collapsed()
                    ->relationship('credential')
                    ->schema([
                        Tabs::make('CredentialsTabs')
                            ->tabs([
                                Tabs\Tab::make('host_tab')
                                    ->label('دسترسی هاست')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Forms\Components\TextInput::make('host_provider')
                                                ->label('ارائه‌دهنده هاست')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('host_panel_url')
                                                ->label('آدرس پنل هاست')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('host_username')
                                                ->label('نام کاربری هاست')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('host_password')
                                                ->label('کلمه عبور هاست')
                                                ->password()
                                                ->revealable()
                                                ->disabled(),
                                        ])
                                    ]),
                                Tabs\Tab::make('domain_tab')
                                    ->label('دسترسی دامنه')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Forms\Components\TextInput::make('domain_provider')
                                                ->label('ثبت‌کننده دامنه')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('domain_panel_url')
                                                ->label('آدرس پنل دامنه')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('domain_username')
                                                ->label('نام کاربری دامنه')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('domain_password')
                                                ->label('کلمه عبور دامنه')
                                                ->password()
                                                ->revealable()
                                                ->disabled(),
                                        ])
                                    ]),
                                Tabs\Tab::make('admin_tab')
                                    ->label('دسترسی مدیریت سایت')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            Forms\Components\TextInput::make('admin_panel_url')
                                                ->label('آدرس پنل مدیریت')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('admin_username')
                                                ->label('نام کاربری مدیریت')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('admin_password')
                                                ->label('کلمه عبور مدیریت')
                                                ->password()
                                                ->revealable()
                                                ->disabled(),
                                        ])
                                    ]),
                                Tabs\Tab::make('other_tab')
                                    ->label('سایر اطلاعات دسترسی')
                                    ->schema([
                                        Forms\Components\Textarea::make('other_credentials')
                                            ->label('جزئیات سایر دسترسی‌ها')
                                            ->disabled(),
                                    ]),
                            ])
                            ->columnSpanFull()
                    ]),

                Section::make('بسته تحویل نهایی پروژه')
                    ->description('اطلاعات تحویل پروژه، مستندات آموزشی و اطلاعات دسترسی نهایی')
                    ->collapsible()
                    ->collapsed()
                    ->relationship('handover')
                    ->schema([
                        Forms\Components\RichEditor::make('congratulations_message')
                            ->label('پیام تبریک و توضیحات نهایی')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Repeater::make('training_videos')
                            ->label('ویدیوهای آموزشی استفاده از سایت')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('عنوان ویدیو')
                                    ->required(),
                                Forms\Components\TextInput::make('url')
                                    ->label('لینک ویدیو (مثال: آپارات/یوتیوب)')
                                    ->url()
                                    ->required(),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('final_credentials')
                            ->label('اطلاعات نهایی دسترسی‌ها (رمزنگاری شده در دیتابیس)')
                            ->placeholder("مثال:\nلینک مدیریت: https://site.com/wp-admin\nنام کاربری: admin\nکلمه عبور: 123456")
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان پروژه')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-briefcase')
                    ->iconColor('primary')
                    ->description(fn (Project $record): ?string => $record->demo_url ? 'دارای لینک دمو' : null),

                Tables\Columns\TextColumn::make('client.name')
                    ->label('مشتری')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user')
                    ->iconColor('gray'),

                Tables\Columns\ViewColumn::make('progress')
                    ->label('پیشرفت و وضعیت پروژه')
                    ->view('filament.tables.columns.project-progress'),

                Tables\Columns\IconColumn::make('is_settled')
                    ->label('تسویه مالی')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('feedback_deadline')
                    ->label('مهلت فیدبک')
                    ->dateTime('Y/m/d H:i')
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->iconColor('warning')
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->dateTime('Y/m/d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت پروژه')
                    ->options([
                        'draft' => 'پیش‌نویس اولیه',
                        'brief' => 'تکمیل بریف نیازمندی‌ها',
                        'contract' => 'امضای قرارداد و امور مالی',
                        'in_progress' => 'در حال طراحی و توسعه',
                        'review' => 'بازنگری و ثبت نظرات (دمو)',
                        'ready_handover' => 'آماده‌سازی بسته تحویل',
                        'completed' => 'تحویل نهایی و خاتمه',
                    ]),

                Tables\Filters\TernaryFilter::make('is_settled')
                    ->label('وضعیت تسویه مالی')
                    ->boolean()
                    ->trueLabel('تسویه‌شده')
                    ->falseLabel('تسویه‌نشده'),
            ])
            ->actions([
                Actions\Action::make('openDemo')
                    ->label('دمو')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('info')
                    ->url(fn (Project $record): ?string => $record->demo_url)
                    ->openUrlInNewTab()
                    ->visible(fn (Project $record): bool => !empty($record->demo_url)),

                Actions\Action::make('sendSms')
                    ->label('ارسال پیامک')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('warning')
                    ->form([
                        Forms\Components\Textarea::make('message')
                            ->label('متن پیامک')
                            ->required()
                            ->rows(4),
                    ])
                            ->action(function (Project $record, array $data) {
                        $client = $record->client;
                        if (!$client || !$client->phone) {
                            \Filament\Notifications\Notification::make()
                                ->title('خطا')
                                ->body('شماره موبایلی برای این مشتری یافت نشد.')
                                ->danger()
                                ->send();
                            return;
                        }
                        
                        \Illuminate\Support\Facades\Log::info("SMS manually sent to {$client->phone} ({$client->name}) for Project {$record->title}: {$data['message']}");
                        
                        \Filament\Notifications\Notification::make()
                            ->title('پیامک با موفقیت ارسال شد')
                            ->body("پیام با موفقیت به شماره {$client->phone} شبیه‌سازی و ارسال گردید.")
                            ->success()
                            ->send();
                    }),
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ContractRelationManager::class,
            RelationManagers\PaymentsRelationManager::class,
            RelationManagers\FeedbacksRelationManager::class,
            RelationManagers\TicketsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
