<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Resources\Pages\EditRecord;

class ManageProjectProforma extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-currency-dollar';

    public static function getNavigationLabel(): string
    {
        return 'صدور پیش‌فاکتور';
    }

    public function getTitle(): string
    {
        return 'مدیریت و صدور پیش‌فاکتور پروژه';
    }

    public function getMaxContentWidth(): \Filament\Support\Enums\Width | string | null
    {
        return \Filament\Support\Enums\Width::Full;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('آیتم‌های پیش‌فاکتور')
                    ->description('موارد و هزینه‌های برآورد شده برای انجام این پروژه را وارد کنید.')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->relationship('proforma')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->label('لیست خدمات و محصولات')
                            ->schema([
                                Forms\Components\TextInput::make('description')
                                    ->label('شرح خدمات')
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('تعداد/مقدار')
                                    ->numeric()
                                    ->default(1)
                                    ->required(),
                                Forms\Components\TextInput::make('unit_price')
                                    ->label('مبلغ واحد (تومان)')
                                    ->numeric()
                                    ->required(),
                            ])
                            ->columns(4)
                            ->defaultItems(1)
                            ->addActionLabel('افزودن ردیف جدید')
                            ->columnSpanFull(),
                            
                        Grid::make(3)->schema([
                            Forms\Components\TextInput::make('total_amount')
                                ->label('مبلغ کل (تومان)')
                                ->numeric()
                                ->default(0)
                                ->required(),
                            Forms\Components\TextInput::make('discount')
                                ->label('تخفیف (تومان)')
                                ->numeric()
                                ->default(0),
                            Forms\Components\TextInput::make('final_amount')
                                ->label('مبلغ قابل پرداخت (تومان)')
                                ->numeric()
                                ->default(0)
                                ->required(),
                        ]),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('توضیحات تکمیلی (دلخواه)')
                            ->rows(3)
                            ->columnSpanFull(),

                        Grid::make(2)->schema([
                            Forms\Components\Toggle::make('is_approved_by_client')
                                ->label('تایید شده توسط مشتری')
                                ->disabled(),
                            Forms\Components\DateTimePicker::make('approved_at')
                                ->label('تاریخ تایید')
                                ->disabled(),
                        ]),
                    ]),
            ]);
    }
}
