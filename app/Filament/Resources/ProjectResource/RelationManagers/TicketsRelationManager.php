<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;

class TicketsRelationManager extends RelationManager
{
    protected static string $relationship = 'tickets';

    protected static ?string $title = 'تیکت‌های پشتیبانی';
    protected static ?string $modelLabel = 'تیکت';
    protected static ?string $pluralModelLabel = 'تیکت‌ها';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('subject')
                    ->label('موضوع تیکت')
                    ->required()
                    ->disabled(fn ($record) => $record !== null)
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->label('وضعیت تیکت')
                    ->options([
                        'open' => 'باز (در انتظار پاسخ)',
                        'replied' => 'پاسخ داده شده',
                        'closed' => 'بسته شده',
                    ])
                    ->default('open')
                    ->required()
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('subject')
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->label('موضوع تیکت')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'danger',
                        'replied' => 'success',
                        'closed' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'open' => 'باز',
                        'replied' => 'پاسخ داده شده',
                        'closed' => 'بسته شده',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->mutateFormDataUsing(fn (array $data) => array_merge($data, [
                        'client_id' => $this->getOwnerRecord()->client_id,
                    ]))
                    ->after(fn ($record) => $record->messages()->create([
                        'sender_id' => auth()->id(),
                        'message' => 'تیکت توسط مدیریت ایجاد شد.',
                    ])),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->after(function ($record, array $data) {
                        if (!empty($data['new_reply'])) {
                            $record->messages()->create([
                                'sender_id' => auth()->id(),
                                'message' => $data['new_reply'],
                            ]);
                            $record->update(['status' => 'replied']);
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
}
