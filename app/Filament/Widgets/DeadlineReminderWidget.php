<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;

class DeadlineReminderWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = [
        'default' => 1,
        'lg' => 1,
    ];
    protected static ?string $heading = 'یادآور ددلاین‌ها و هشدارهای زمانی';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Project::query()
                    ->whereNotNull('feedback_deadline')
                    ->orWhere('is_settled', false)
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('پروژه')
                    ->weight('bold')
                    ->description(fn (Project $record) => $record->getStatusLabel()),

                Tables\Columns\TextColumn::make('deadline_status')
                    ->label('وضعیت زمان‌بندی')
                    ->badge()
                    ->getStateUsing(function (Project $record) {
                        if ($record->feedback_deadline) {
                            $now = Carbon::now();
                            $deadline = Carbon::parse($record->feedback_deadline);
                            
                            if ($now->greaterThan($deadline)) {
                                $daysOverdue = (int) $deadline->diffInDays($now);
                                return "⚠️ تاخیر: {$daysOverdue} روز گذشته";
                            } else {
                                $daysLeft = (int) $now->diffInDays($deadline);
                                return "⏳ {$daysLeft} روز باقی‌مانده";
                            }
                        }

                        if (! $record->is_settled && $record->status === 'completed') {
                            $daysFromCreated = (int) Carbon::parse($record->updated_at)->diffInDays(Carbon::now());
                            return "💳 {$daysFromCreated} روز از تحویل بدون تسویه";
                        }

                        return 'عادی';
                    })
                    ->color(function (string $state) {
                        if (str_contains($state, 'تاخیر')) return 'danger';
                        if (str_contains($state, 'باقی‌مانده')) return 'warning';
                        if (str_contains($state, 'بدون تسویه')) return 'rose';
                        return 'gray';
                    }),
            ])
            ->actions([
                \Filament\Actions\Action::make('manage')
                    ->label('بررسی')
                    ->icon('heroicon-o-arrow-left')
                    ->color('warning')
                    ->url(fn (Project $record) => route('filament.admin.resources.projects.index', ['tableSearch' => $record->title])),
            ])
            ->paginated([5]);
    }
}
