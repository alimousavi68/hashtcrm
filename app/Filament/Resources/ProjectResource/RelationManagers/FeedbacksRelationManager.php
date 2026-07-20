<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class FeedbacksRelationManager extends RelationManager
{
    protected static string $relationship = 'feedbacks';

    protected static ?string $title = 'بازخوردهای دمو';
    protected static ?string $modelLabel = 'بازخورد';
    protected static ?string $pluralModelLabel = 'بازخوردها';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('notes')
                    ->label('نظرات و بازخورد')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->label('وضعیت')
                    ->options([
                        'pending' => 'در انتظار بررسی',
                        'approved' => 'تایید شده (دمو پذیرفته شد)',
                        'needs_changes' => 'نیاز به اصلاحات',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('notes')
                    ->label('نظرات')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'needs_changes' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'در انتظار بررسی',
                        'approved' => 'تایید شده',
                        'needs_changes' => 'نیاز به اصلاحات',
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
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
