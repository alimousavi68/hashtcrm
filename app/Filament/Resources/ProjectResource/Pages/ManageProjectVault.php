<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Resources\Pages\EditRecord;

class ManageProjectVault extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-key';

    public static function getNavigationLabel(): string
    {
        return 'گاوصندوق دسترسی‌ها';
    }

    public function getTitle(): string
    {
        return 'گاوصندوق امن دسترسی‌ها و اطلاعات حساس پروژه';
    }

    public function getMaxContentWidth(): \Filament\Support\Enums\Width | string | null
    {
        return \Filament\Support\Enums\Width::Full;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('اطلاعات امنیتی و دسترسی‌ها (Vault)')
                    ->description('اطلاعات محرمانه ثبت‌شده توسط مشتری. این داده‌ها با کلید اختصاصی پروژه در دیتابیس به صورت رمزنگاری‌شده (AES-256) ذخیره می‌شوند.')
                    ->icon('heroicon-o-lock-closed')
                    ->relationship('credential')
                    ->schema([
                        Tabs::make('CredentialsTabs')
                            ->tabs([
                                Tabs\Tab::make('host_tab')
                                    ->label('دسترسی هاست')
                                    ->icon('heroicon-o-server')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Forms\Components\TextInput::make('host_provider')
                                                ->label('ارائه‌دهنده هاست'),
                                            Forms\Components\TextInput::make('host_panel_url')
                                                ->label('آدرس پنل مدیریت هاست')
                                                ->url(),
                                            Forms\Components\TextInput::make('host_username')
                                                ->label('نام کاربری هاست'),
                                            Forms\Components\TextInput::make('host_password')
                                                ->label('کلمه عبور هاست')
                                                ->password()
                                                ->revealable(),
                                        ])
                                    ]),

                                Tabs\Tab::make('domain_tab')
                                    ->label('دسترسی دامنه')
                                    ->icon('heroicon-o-globe-alt')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Forms\Components\TextInput::make('domain_provider')
                                                ->label('ثبت‌کننده دامنه'),
                                            Forms\Components\TextInput::make('domain_panel_url')
                                                ->label('آدرس پنل مدیریت دامنه')
                                                ->url(),
                                            Forms\Components\TextInput::make('domain_username')
                                                ->label('نام کاربری دامنه'),
                                            Forms\Components\TextInput::make('domain_password')
                                                ->label('کلمه عبور دامنه')
                                                ->password()
                                                ->revealable(),
                                        ])
                                    ]),

                                Tabs\Tab::make('admin_tab')
                                    ->label('دسترسی پنل مدیریت سایت')
                                    ->icon('heroicon-o-command-line')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            Forms\Components\TextInput::make('admin_panel_url')
                                                ->label('آدرس ورود مدیریت (مثال: wp-admin)')
                                                ->url(),
                                            Forms\Components\TextInput::make('admin_username')
                                                ->label('نام کاربری مدیر'),
                                            Forms\Components\TextInput::make('admin_password')
                                                ->label('کلمه عبور مدیر')
                                                ->password()
                                                ->revealable(),
                                        ])
                                    ]),

                                Tabs\Tab::make('other_tab')
                                    ->label('سایر دسترسی‌ها')
                                    ->icon('heroicon-o-document-text')
                                    ->schema([
                                        Forms\Components\Textarea::make('other_credentials')
                                            ->label('جزئیات و دسترسی‌های تکمیلی (داده‌های حساس)')
                                            ->rows(6),
                                    ]),
                            ])
                            ->columnSpanFull()
                    ]),
            ]);
    }
}
