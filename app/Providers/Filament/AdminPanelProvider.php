<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Pages\Auth\CustomLogin::class)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->font(
                'PeydaWebVF',
                url: asset('fonts/peyda/fontiran.css'),
                provider: \Filament\FontProviders\LocalFontProvider::class,
            )
            ->databaseNotifications()
            ->plugins([
                \Prodstarter\FilamentNotificationCenter\FilamentNotificationCenterPlugin::make()
                    ->categories([
                        \Prodstarter\FilamentNotificationCenter\NotificationCenterCategory::make('projects')
                            ->label('پروژه‌ها')
                            ->icon('heroicon-o-folder'),
                        \Prodstarter\FilamentNotificationCenter\NotificationCenterCategory::make('financial')
                            ->label('مالی و قراردادها')
                            ->icon('heroicon-o-credit-card'),
                        \Prodstarter\FilamentNotificationCenter\NotificationCenterCategory::make('tickets')
                            ->label('پشتیبانی')
                            ->icon('heroicon-o-chat-bubble-left-right'),
                        \Prodstarter\FilamentNotificationCenter\NotificationCenterCategory::make('system')
                            ->label('سیستم')
                            ->icon('heroicon-o-cpu-chip'),
                    ]),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\ActiveProjectsProgressWidget::class,
                \App\Filament\Widgets\DeadlineReminderWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
