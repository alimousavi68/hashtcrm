<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContractRelationManager extends RelationManager
{
    protected static string $relationship = 'contract';

    protected static ?string $title = 'قرارداد پروژه';
    protected static ?string $modelLabel = 'قرارداد';
    protected static ?string $pluralModelLabel = 'قراردادها';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('عنوان قرارداد')
                    ->required()
                    ->default('قرارداد همکاری طراحی و توسعه سایت')
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('content')
                    ->label('متن قرارداد')
                    ->required()
                    ->placeholder('متن قرارداد را در اینجا بنویسید...')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('signed_at')
                    ->label('تاریخ امضا')
                    ->disabled(),
                Forms\Components\TextInput::make('signature_name')
                    ->label('نام و نام خانوادگی امضاکننده')
                    ->disabled(),
                Forms\Components\TextInput::make('signature_national_code')
                    ->label('کد ملی امضاکننده')
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان قرارداد'),
                Tables\Columns\TextColumn::make('signed_at')
                    ->label('تاریخ امضاء')
                    ->dateTime('Y/m/d H:i')
                    ->placeholder('هنوز امضا نشده است'),
                Tables\Columns\TextColumn::make('signature_name')
                    ->label('امضاکننده')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('signature_national_code')
                    ->label('کد ملی')
                    ->placeholder('-'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(fn ($livewire) => $livewire->getOwnerRecord()->contract()->count() === 0), // Show only if no contract exists
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
