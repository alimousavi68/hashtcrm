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

                            Forms\Components\Select::make('status')
                                ->label('فاز و وضعیت پروژه')
                                ->options([
                                    'draft' => '۱. پیش‌نویس اولیه (۱۰٪)',
                                    'brief' => '۲. تکمیل بریف نیازمندی‌ها (۲۵٪)',
                                    'contract' => '۳. امضای قرارداد و امور مالی (۴۵٪)',
                                    'in_progress' => '۴. در حال طراحی و توسعه (۶۵٪)',
                                    'review' => '۵. بازنگری و ثبت نظرات dmo (۸۰٪)',
                                    'ready_handover' => '۶. آماده‌سازی بسته تحویل (۹۰٪)',
                                    'completed' => '۷. تحویل نهایی و خاتمه (۱۰۰٪)',
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
            'brief' => Pages\ManageProjectBrief::route('/{record}/brief'),
            'contract' => Pages\ManageProjectContract::route('/{record}/contract'),
            'payments' => Pages\ManageProjectPayments::route('/{record}/payments'),
            'feedbacks' => Pages\ManageProjectFeedbacks::route('/{record}/feedbacks'),
            'vault' => Pages\ManageProjectVault::route('/{record}/vault'),
            'handover' => Pages\ManageProjectHandover::route('/{record}/handover'),
            'tickets' => Pages\ManageProjectTickets::route('/{record}/tickets'),
        ];
    }
}
