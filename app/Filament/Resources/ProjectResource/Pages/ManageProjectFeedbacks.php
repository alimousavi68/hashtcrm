<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\Feedback;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class ManageProjectFeedbacks extends ManageRelatedRecords
{
    protected static string $resource = ProjectResource::class;

    protected static string $relationship = 'feedbacks';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-eye';

    public static function getNavigationLabel(): string
    {
        return 'بازنگری و فیدبک‌ها';
    }

    public function getTitle(): string
    {
        return 'مدیریت نظرات، بازخوردها و مهلت بررسی دمو';
    }

    public function getMaxContentWidth(): \Filament\Support\Enums\Width | string | null
    {
        return \Filament\Support\Enums\Width::Full;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Textarea::make('notes')
                    ->label('متن بازخورد / اصلاحات مورد نیاز')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->label('وضعیت بازخورد')
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
                    ->label('نظرات و توضیحات مشتری')
                    ->wrap()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'needs_changes' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'در انتظار بررسی',
                        'approved' => 'تایید شده',
                        'needs_changes' => 'نیاز به اصلاحات',
                        default => $state,
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
            ])
            ->headerActions([
                Actions\Action::make('updateDemoConfig')
                    ->label('تنظیم لینک دمو و مهلت فیدبک')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->color('info')
                    ->form([
                        Forms\Components\TextInput::make('demo_url')
                            ->label('لینک پیش‌نمایش دمو')
                            ->url()
                            ->default(fn () => $this->getOwnerRecord()->demo_url),
                        Forms\Components\DateTimePicker::make('feedback_deadline')
                            ->label('مهلت زمان ارسال فیدبک (تایمر خودکار)')
                            ->default(fn () => $this->getOwnerRecord()->feedback_deadline),
                    ])
                    ->action(function (array $data) {
                        $project = $this->getOwnerRecord();
                        $project->update([
                            'demo_url' => $data['demo_url'],
                            'feedback_deadline' => $data['feedback_deadline'],
                        ]);

                        Notification::make()
                            ->title('اطلاعات دمو به‌روزرسانی شد')
                            ->success()
                            ->send();
                    }),

                Actions\CreateAction::make()
                    ->label('ثبت نظر جدید'),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ]);
    }
}
