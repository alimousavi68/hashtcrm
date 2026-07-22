<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationLabel = 'تیکت‌های پشتیبانی';
    protected static ?string $pluralModelLabel = 'تیکت‌های پشتیبانی';
    protected static ?string $modelLabel = 'تیکت پشتیبانی';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('is_read_by_admin', false)->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger';
    }

    public static function getNavigationItems(): array
    {
        $unreadCount = static::getModel()::where('is_read_by_admin', false)->count();
        $badge = $unreadCount > 0 ? (string) $unreadCount : null;

        return [
            \Filament\Navigation\NavigationItem::make('صندوق پیام‌ها')
                ->url(static::getUrl('index'))
                ->icon('heroicon-o-inbox')
                ->badge($badge, 'danger')
                ->group('پشتیبانی و تیکت‌ها')
                ->sort(1),
            \Filament\Navigation\NavigationItem::make('تیکت جدید')
                ->url(static::getUrl('create'))
                ->icon('heroicon-o-plus-circle')
                ->group('پشتیبانی و تیکت‌ها')
                ->sort(2),
            \Filament\Navigation\NavigationItem::make('پاسخ داده شده')
                ->url(static::getUrl('index', ['tableFilters' => ['status' => ['value' => 'replied']]]))
                ->icon('heroicon-o-paper-airplane')
                ->group('پشتیبانی و تیکت‌ها')
                ->sort(3),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('جزئیات تیکت')
                    ->schema([
                        Grid::make(3)->schema([
                            Forms\Components\Select::make('client_id')
                                ->label('مشتری')
                                ->relationship('client', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->disabled(fn ($record) => $record !== null),
                            Forms\Components\Select::make('project_id')
                                ->label('پروژه مربوطه')
                                ->relationship('project', 'title')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->disabled(fn ($record) => $record !== null),
                            Forms\Components\Select::make('status')
                                ->label('وضعیت تیکت')
                                ->options([
                                    'open' => 'باز (در انتظار پاسخ)',
                                    'replied' => 'پاسخ داده شده',
                                    'closed' => 'بسته شده',
                                ])
                                ->default('open')
                                ->required(),
                        ]),
                        Forms\Components\TextInput::make('subject')
                            ->label('موضوع تیکت')
                            ->required()
                            ->disabled(fn ($record) => $record !== null)
                            ->columnSpanFull(),
                        Forms\Components\Placeholder::make('messages_history')
                            ->label('تاریخچه گفتگو')
                            ->visible(fn ($record) => $record !== null)
                            ->content(fn ($record) => new \Illuminate\Support\HtmlString(
                                view('filament.components.ticket-messages-history', ['ticket' => $record])->render()
                            ))
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('new_reply')
                            ->label('ارسال پاسخ جدید')
                            ->placeholder('پاسخ خود را در این بخش بنویسید...')
                            ->visible(fn ($record) => $record !== null)
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->label('موضوع تیکت')
                    ->searchable()
                    ->sortable()
                    ->weight(fn ($record) => $record->is_read_by_admin ? 'normal' : 'bold')
                    ->description(fn ($record) => $record->is_read_by_admin ? null : '● پیام جدید (خوانده نشده)'),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('مشتری')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.title')
                    ->label('پروژه')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'danger',
                        'replied' => 'success',
                        'closed' => 'gray',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'open' => 'باز (جدید)',
                        'replied' => 'پاسخ داده شده',
                        'closed' => 'بسته شده',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخرین تغییر')
                    ->formatStateUsing(fn ($state) => \App\Helpers\JalaliHelper::toJalali($state, 'Y/m/d H:i'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت تیکت')
                    ->options([
                        'open' => 'باز',
                        'replied' => 'پاسخ داده شده',
                        'closed' => 'بسته شده',
                    ]),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->label('مشاهده و پاسخ')
                    ->before(function ($record) {
                        if (!$record->is_read_by_admin) {
                            $record->update(['is_read_by_admin' => true]);
                        }
                    })
                    ->after(function ($record, array $data) {
                        if (!empty($data['new_reply'])) {
                            $record->messages()->create([
                                'sender_id' => auth()->id(),
                                'message' => $data['new_reply'],
                            ]);
                            $record->update([
                                'status' => 'replied',
                                'is_read_by_admin' => true,
                                'is_read_by_client' => false,
                                'updated_at' => now(),
                            ]);

                            // Send notification to client
                            if ($record->client) {
                                $record->client->notify(new \App\Notifications\ProjectNotification(
                                    $record->project,
                                    'پاسخ جدید به تیکت پشتیبانی',
                                    "پشتیبان به تیکت «{$record->subject}» پاسخ داد.",
                                    'tickets'
                                ));
                            }
                        }
                    }),
                Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
