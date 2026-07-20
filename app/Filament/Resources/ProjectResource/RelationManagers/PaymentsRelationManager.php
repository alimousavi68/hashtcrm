<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'پرداخت‌های پروژه';
    protected static ?string $modelLabel = 'پرداخت';
    protected static ?string $pluralModelLabel = 'پرداخت‌ها';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->label('مبلغ (تومان)')
                    ->numeric()
                    ->required(),
                Forms\Components\FileUpload::make('bank_slip_path')
                    ->label('تصویر فیش واریزی')
                    ->image()
                    ->directory('bank-slips')
                    ->nullable(),
                Forms\Components\Select::make('status')
                    ->label('وضعیت پرداخت')
                    ->options([
                        'pending' => 'در انتظار بررسی',
                        'approved' => 'تایید شده',
                        'rejected' => 'رد شده',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\DateTimePicker::make('verified_at')
                    ->label('تاریخ تایید/رد')
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('amount')
            ->columns([
                Tables\Columns\TextColumn::make('amount')
                    ->label('مبلغ (تومان)')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => number_format($state) . ' تومان')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'در انتظار بررسی',
                        'approved' => 'تایید شده',
                        'rejected' => 'رد شده',
                    })
                    ->sortable(),
                Tables\Columns\ImageColumn::make('bank_slip_path')
                    ->label('تصویر فیش'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت فیش')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('verified_at')
                    ->label('تاریخ بررسی')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\CreateAction::make(),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->mutateRecordDataUsing(fn (array $data) => array_merge($data, [
                        'verified_at' => $data['status'] !== 'pending' && !$data['verified_at'] ? now() : $data['verified_at'],
                    ])),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
