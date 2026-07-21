<?php

namespace App\Filament\Resources\BriefTemplates\Tables;

use App\Models\BriefTemplate;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class BriefTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('نام الگو')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-document-text')
                    ->iconColor('primary')
                    ->description(function (BriefTemplate $record): string {
                        $count = count($record->schema ?? []);
                        return "تعداد فیلدهای فرم: {$count} فیلد";
                    }),

                IconColumn::make('is_active')
                    ->label('وضعیت انتشار')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->dateTime('Y/m/d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('updated_at')
                    ->label('آخرین بهینه‌سازی')
                    ->dateTime('Y/m/d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('وضعیت انتشار')
                    ->boolean()
                    ->trueLabel('فقط الگوهای فعال')
                    ->falseLabel('فقط الگوهای غیرفعال'),
            ])
            ->recordActions([
                Action::make('previewClientForm')
                    ->label('پیش‌نمایش فرم')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading(fn (BriefTemplate $record): string => "پیش‌نمایش نحوه نمایش فرم: «{$record->name}»")
                    ->modalWidth('3xl')
                    ->modalContent(fn (BriefTemplate $record): \Illuminate\Contracts\View\View => view('filament.admin.brief-template-preview', ['record' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('بستن پیش‌نمایش'),

                EditAction::make()
                    ->label('ویرایش الگو')
                    ->icon('heroicon-o-pencil-square'),

                Action::make('toggleActive')
                    ->label(fn (BriefTemplate $record): string => $record->is_active ? 'غیرفعال‌سازی' : 'فعال‌سازی')
                    ->icon(fn (BriefTemplate $record): string => $record->is_active ? 'heroicon-o-pause-circle' : 'heroicon-o-play-circle')
                    ->color(fn (BriefTemplate $record): string => $record->is_active ? 'warning' : 'success')
                    ->action(function (BriefTemplate $record) {
                        $record->update(['is_active' => !$record->is_active]);

                        Notification::make()
                            ->title('تغییر وضعیت انجام شد')
                            ->body("وضعیت الگوی «{$record->name}» تغییر یافت.")
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
