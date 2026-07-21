<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class ActiveProjectsProgressWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'پروژه‌های در حال اجرا و میزان پیشرفت';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Project::query()
                    ->with(['client'])
                    ->where('status', '!=', 'completed')
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان پروژه')
                    ->searchable()
                    ->description(fn (Project $record) => $record->client?->name ? 'مشتری: ' . $record->client->name : 'مشتری نامشخص')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('status')
                    ->label('مرحله فعلی')
                    ->badge()
                    ->getStateUsing(fn (Project $record) => $record->getStatusLabel())
                    ->color(fn (string $state): string => match ($state) {
                        'پیش‌نویس اولیه' => 'gray',
                        'تکمیل بریف نیازمندی‌ها' => 'info',
                        'امضای قرارداد و امور مالی' => 'warning',
                        'در حال طراحی و توسعه' => 'primary',
                        'بازنگری و ثبت نظرات (دمو)' => 'purple',
                        'آماده‌سازی بسته تحویل' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('progress_percent')
                    ->label('درصد پیشرفت')
                    ->formatStateUsing(function ($state, Project $record) {
                        $percent = $record->getProgressPercent();
                        $colorClass = match (true) {
                            $percent >= 80 => 'bg-emerald-500',
                            $percent >= 50 => 'bg-amber-500',
                            default => 'bg-primary-600',
                        };

                        return new HtmlString("
                            <div class='w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3.5 min-w-[140px] overflow-hidden flex items-center relative'>
                                <div class='{$colorClass} h-3.5 rounded-full transition-all duration-500' style='width: {$percent}%'></div>
                                <span class='absolute inset-0 flex items-center justify-center text-[10px] font-bold text-gray-800 dark:text-gray-200 drop-shadow-sm'>{$percent}%</span>
                            </div>
                        ");
                    }),

                Tables\Columns\IconColumn::make('is_settled')
                    ->label('وضعیت تسویه')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->tooltip(fn (Project $record) => $record->is_settled ? 'تصفیه شده' : 'تسویه نشده'),

                Tables\Columns\TextColumn::make('feedback_deadline')
                    ->label('مهلت بازخورد/ددلاین')
                    ->dateTime('Y/m/d')
                    ->placeholder('ثبت‌نشده')
                    ->sortable(),
            ])
            ->actions([
                \Filament\Actions\Action::make('viewProject')
                    ->label('مشاهده')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->url(fn (Project $record) => route('filament.admin.resources.projects.index', ['tableSearch' => $record->title])),
            ])
            ->paginated([5, 10]);
    }
}
