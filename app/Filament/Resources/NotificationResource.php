<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Database\Eloquent\Builder;

class NotificationResource extends Resource
{
    protected static ?string $model = DatabaseNotification::class;

    protected static ?string $navigationLabel = 'رویدادها و اعلانات';
    protected static ?string $pluralModelLabel = 'رویدادها و اعلانات';
    protected static ?string $modelLabel = 'اعلان';
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-bell';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('جزئیات رویداد')
                    ->schema([
                        Forms\Components\Placeholder::make('title')
                            ->label('عنوان رویداد')
                            ->content(fn ($record) => $record->data['title'] ?? 'رویداد سیستم'),
                        Forms\Components\Placeholder::make('message')
                            ->label('جزئیات پیام')
                            ->content(fn ($record) => $record->data['message'] ?? '-'),
                        Forms\Components\Placeholder::make('created_at')
                            ->label('زمان ثبت رویداد')
                            ->content(fn ($record) => $record->created_at->format('Y/m/d H:i')),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('data.title')
                    ->label('عنوان رویداد')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('data.message')
                    ->label('پیام')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('notifiable.name')
                    ->label('گیرنده')
                    ->searchable(),
                Tables\Columns\IconColumn::make('read_at')
                    ->label('خوانده شده')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->read_at !== null)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('read_at')
                    ->label('وضعیت مطالعه')
                    ->nullable()
                    ->trueLabel('خوانده شده')
                    ->falseLabel('خوانده نشده')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('read_at'),
                        false: fn (Builder $query) => $query->whereNull('read_at'),
                    ),
            ])
            ->actions([
                Actions\Action::make('markAsRead')
                    ->label('علامت‌گذاری به عنوان خوانده شده')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => $record->read_at === null)
                    ->action(fn ($record) => $record->markAsRead()),
                Actions\ViewAction::make(),
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
            'index' => Pages\ListNotifications::route('/'),
        ];
    }
}
