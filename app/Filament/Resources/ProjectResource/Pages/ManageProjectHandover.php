<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Resources\Pages\EditRecord;

class ManageProjectHandover extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-gift';

    public static function getNavigationLabel(): string
    {
        return 'بسته تحویل نهایی';
    }

    public function getTitle(): string
    {
        return 'مدیریت بسته تحویل نهایی و آموزش‌های پروژه';
    }

    public function getMaxContentWidth(): \Filament\Support\Enums\Width | string | null
    {
        return \Filament\Support\Enums\Width::Full;
    }

    public function form(Schema $schema): Schema
    {
        $isSettled = $this->getRecord()->is_settled;

        return $schema
            ->schema([
                Forms\Components\Placeholder::make('settlement_status_alert')
                    ->label('')
                    ->content(fn () => new \Illuminate\Support\HtmlString(
                        $isSettled
                            ? '<div class="p-4 mb-4 text-sm text-emerald-800 rounded-lg bg-emerald-50 dark:bg-gray-800 dark:text-emerald-400 font-medium flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> وضعیت مالی پروژه تسویه شده است. مشتری به تمامی محتویات این بسته در پنل کلاینت دسترسی دارد.</div>'
                            : '<div class="p-4 mb-4 text-sm text-amber-800 rounded-lg bg-amber-50 dark:bg-gray-800 dark:text-amber-400 font-medium flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> توجه: این پروژه هنوز تسویه حساب مالی کامل نشده است. محتویات این بسته تا زمان تسویه کامل در پنل مشتری قفل خواهد ماند.</div>'
                    ))
                    ->columnSpanFull(),

                Section::make('اطلاعات بسته تحویل نهایی (Handover)')
                    ->description('اطلاعات تحویل، مستندات و ویدیوهای آموزشی پس از خاتمه پروژه')
                    ->icon('heroicon-o-gift')
                    ->relationship('handover')
                    ->schema([
                        Forms\Components\RichEditor::make('congratulations_message')
                            ->label('پیام تبریک و توضیحات تحویل پروژه')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Repeater::make('training_videos')
                            ->label('ویدیوهای آموزشی کار با سایت و پنل')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('عنوان ویدیو آموزشی')
                                    ->required(),
                                Forms\Components\TextInput::make('url')
                                    ->label('لینک ویدیو (مثال: آپارات، یوتیوب، کاویار)')
                                    ->url()
                                    ->required(),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('final_credentials')
                            ->label('اطلاعات نهایی دسترسی‌ها (رمزنگاری شده با کست encrypted)')
                            ->placeholder("مثال:\nآدرس مدیریت: https://site.com/wp-admin\nنام کاربری: admin\nکلمه عبور: 123456")
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
            ]);
    }
}
