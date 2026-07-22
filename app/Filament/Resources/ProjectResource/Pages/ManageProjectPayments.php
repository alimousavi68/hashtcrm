<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\Payment;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class ManageProjectPayments extends ManageRelatedRecords
{
    protected static string $resource = ProjectResource::class;

    protected static string $relationship = 'payments';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-credit-card';

    public static function getNavigationLabel(): string
    {
        return 'امور مالی و پرداخت‌ها';
    }

    public function getTitle(): string
    {
        return 'مدیریت پرداخت‌ها و فیش‌های بانکی پروژه';
    }

    public function getMaxContentWidth(): \Filament\Support\Enums\Width | string | null
    {
        return \Filament\Support\Enums\Width::Full;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->label('مبلغ پرداخت (تومان)')
                    ->numeric()
                    ->required(),
                Forms\Components\FileUpload::make('bank_slip_path')
                    ->label('تصویر فیش واریزی')
                    ->image()
                    ->directory('bank-slips')
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->label('وضعیت فیش')
                    ->options([
                        'pending' => 'در انتظار بررسی',
                        'approved' => 'تایید شده',
                        'rejected' => 'رد شده',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\DateTimePicker::make('verified_at')
                    ->label('تاریخ بررسی')
                    ->displayFormat('Y/m/d H:i')
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
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-banknotes')
                    ->iconColor('success'),

                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'در انتظار بررسی',
                        'approved' => 'تایید شده',
                        'rejected' => 'رد شده',
                        default => $state,
                    })
                    ->sortable(),

                Tables\Columns\ImageColumn::make('bank_slip_path')
                    ->label('تصویر فیش')
                    ->circular()
                    ->square(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت فیش')
                    ->formatStateUsing(fn ($state) => \App\Helpers\JalaliHelper::toJalali($state, 'Y/m/d H:i'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('verified_at')
                    ->label('تاریخ بررسی')
                    ->formatStateUsing(fn ($state) => \App\Helpers\JalaliHelper::toJalali($state, 'Y/m/d H:i'))
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'pending' => 'در انتظار بررسی',
                        'approved' => 'تایید شده',
                        'rejected' => 'رد شده',
                    ]),
            ])
            ->headerActions([
                Actions\Action::make('toggleSettlement')
                    ->label(fn () => $this->getOwnerRecord()->is_settled ? 'تغییر به: تسویه‌نشده' : 'تایید تسویه حساب کامل پروژه')
                    ->icon(fn () => $this->getOwnerRecord()->is_settled ? 'heroicon-o-x-circle' : 'heroicon-o-check-badge')
                    ->color(fn () => $this->getOwnerRecord()->is_settled ? 'danger' : 'success')
                    ->requiresConfirmation()
                    ->modalHeading('تغییر وضعیت تسویه حساب مالی')
                    ->modalDescription(fn () => $this->getOwnerRecord()->is_settled
                        ? 'آیا از لغو تسویه حساب کامل این پروژه اطمینان دارید؟ در این صورت دسترسی کلاینت به بسته تحویل نهایی غیرفعال خواهد شد.'
                        : 'با تایید تسویه حساب، دسترسی کلاینت به بسته تحویل نهایی و دارایی‌های پروژه فعال می‌شود.')
                    ->action(function () {
                        $project = $this->getOwnerRecord();
                        $project->update(['is_settled' => !$project->is_settled]);

                        Notification::make()
                            ->title('وضعیت تسویه مالی به‌روزرسانی شد')
                            ->body($project->is_settled ? 'پروژه با موفقیت تسویه حساب شد.' : 'تسویه حساب پروژه لغو گردید.')
                            ->success()
                            ->send();
                    }),
                Actions\CreateAction::make()
                    ->label('ثبت فیش واریزی جدید'),
            ])
            ->actions([
                Actions\Action::make('approve')
                    ->label('تایید فیش')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Payment $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (Payment $record) {
                        $record->update([
                            'status' => 'approved',
                            'verified_at' => now(),
                        ]);

                        Notification::make()
                            ->title('فیش بانکی تایید شد')
                            ->success()
                            ->send();
                    }),
                Actions\Action::make('reject')
                    ->label('رد فیش')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn (Payment $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (Payment $record) {
                        $record->update([
                            'status' => 'rejected',
                            'verified_at' => now(),
                        ]);

                        Notification::make()
                            ->title('فیش بانکی رد شد')
                            ->danger()
                            ->send();
                    }),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ]);
    }
}
