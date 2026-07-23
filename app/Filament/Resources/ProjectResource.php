<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationLabel = 'پروژه‌ها';
    protected static ?string $pluralModelLabel = 'پروژه‌ها';
    protected static ?string $modelLabel = 'پروژه';
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-folder-open';
    protected static ?int $navigationSort = 1;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\EditProject::class,
            Pages\ManageProjectBrief::class,
            Pages\ManageProjectProforma::class,
            Pages\ManageProjectContract::class,
            Pages\ManageProjectPayments::class,
            Pages\ManageProjectFeedbacks::class,
            Pages\ManageProjectVault::class,
            Pages\ManageProjectHandover::class,
            Pages\ManageProjectTickets::class,
        ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('اطلاعات اصلی و فاز پروژه')
                    ->description('اطلاعات پایه پروژه، فاز جاری توسعه و وضعیت تسویه حساب مالی')
                    ->icon('heroicon-o-briefcase')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('عنوان پروژه')
                                ->required()
                                ->placeholder('مثال: طراحی سایت فروشگاهی هشت بایت'),

                            Forms\Components\Select::make('client_id')
                                ->label('مشتری (کارفرما)')
                                ->relationship(
                                    name: 'client',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: fn (Builder $query) => $query->where('role', 'client')
                                )
                                ->searchable()
                                ->preload()
                                ->required(),

                            Forms\Components\Select::make('brief_template_id')
                                ->label('الگوی پرسشنامه (بریف نیازمندی‌ها)')
                                ->options(fn () => \App\Models\BriefTemplate::where('is_active', true)->pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->nullable()
                                ->live()
                                ->afterStateUpdated(function ($state, $set, $get) {
                                    if ($state) {
                                        $template = \App\Models\BriefTemplate::find($state);
                                        if ($template && !empty($template->schema)) {
                                            $set('brief_schema', $template->schema);
                                            // اگر فاز پروژه پیش‌نویس بود، آن را به بریف تغییر می‌دهیم
                                            if ($get('status') === 'draft' || empty($get('status'))) {
                                                $set('status', 'brief');
                                            }
                                        }
                                    }
                                })
                                ->helperText('با انتخاب الگو، فیلدهای پرسشنامه به صورت خودکار به پروژه متصل شده و فاز به «تکمیل بریف» تغییر می‌یابد.'),

                            Forms\Components\Select::make('status')
                                ->label('فاز و وضعیت پروژه')
                                ->options([
                                    'draft' => '۱. پیش‌نویس اولیه',
                                    'brief' => '۲. تکمیل بریف نیازمندی‌ها',
                                    'proforma' => '۳. صدور پیش‌فاکتور',
                                    'contract' => '۴. امضای قرارداد و پیش‌پرداخت',
                                    'ui_design' => '۵. طراحی رابط کاربری (UI)',
                                    'development' => '۶. توسعه و برنامه‌نویسی',
                                    'review' => '۷. بازنگری و دمو نهایی',
                                    'ready_handover' => '۸. آماده‌سازی بسته تحویل',
                                    'completed' => '۹. تحویل نهایی و خاتمه',
                                ])
                                ->default('draft')
                                ->required(),

                            Forms\Components\Toggle::make('is_settled')
                                ->label('تسویه حساب کامل مالی')
                                ->helperText('در صورت تایید، مشتری به بسته تحویل نهایی دسترسی پیدا می‌کند.')
                                ->default(false)
                                ->required(),

                            Forms\Components\DateTimePicker::make('feedback_deadline')
                                ->label('مهلت زمان ارسال بازنگری و فیدبک')
                                ->helperText('در صورت اتمام ددلاین، پروژه به صورت خودکار تایید می‌شود.')
                                ->displayFormat('Y/m/d H:i')
                                ->nullable(),

                            Forms\Components\TextInput::make('demo_url')
                                ->label('لینک پیش‌نمایش دمو پروژه')
                                ->placeholder('https://demo.example.com')
                                ->url()
                                ->nullable(),
                        ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'default' => 1,
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Tables\Columns\ViewColumn::make('card')
                    ->label('پروژه‌ها')
                    ->view('filament.tables.columns.project-card-view')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('title', 'like', "%{$search}%")
                            ->orWhereHas('client', function (Builder $q) use ($search) {
                                $q->where('name', 'like', "%{$search}%")
                                  ->orWhere('phone', 'like', "%{$search}%");
                            });
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('فاز و وضعیت پروژه')
                    ->options([
                        'draft' => 'پیش‌نویس اولیه',
                        'brief' => 'تکمیل بریف نیازمندی‌ها',
                        'proforma' => 'صدور پیش‌فاکتور',
                        'contract' => 'امضای قرارداد و پیش‌پرداخت',
                        'ui_design' => 'طراحی رابط کاربری (UI)',
                        'development' => 'توسعه و برنامه‌نویسی',
                        'review' => 'بازنگری و دمو نهایی',
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
            'brief' => Pages\ManageProjectBrief::route('/{record}/brief'),
            'proforma' => Pages\ManageProjectProforma::route('/{record}/proforma'),
            'contract' => Pages\ManageProjectContract::route('/{record}/contract'),
            'payments' => Pages\ManageProjectPayments::route('/{record}/payments'),
            'feedbacks' => Pages\ManageProjectFeedbacks::route('/{record}/feedbacks'),
            'vault' => Pages\ManageProjectVault::route('/{record}/vault'),
            'handover' => Pages\ManageProjectHandover::route('/{record}/handover'),
            'tickets' => Pages\ManageProjectTickets::route('/{record}/tickets'),
        ];
    }
}
