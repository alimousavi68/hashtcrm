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

class ClientPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('client')
            ->path('client')
            ->authGuard('client')
            ->login(\App\Filament\Pages\Auth\CustomLogin::class)
            ->brandName('سامانه مدیریت پروژه‌های هشت بهشت')
            ->brandLogo(fn () => new \Illuminate\Support\HtmlString('
                <div style="display: flex; align-items: center; gap: 8px; font-family: PeydaWebVF, sans-serif;">
                    <div style="width: 30px; height: 30px; border-radius: 8px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); display: flex; align-items: center; justify-content: center; color: #ffffff; box-shadow: 0 2px 4px rgba(79, 70, 229, 0.2); flex-shrink: 0;">
                        <svg style="width: 17px; height: 17px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span style="font-size: 15px; font-weight: 800; color: var(--gray-900, #0f172a); letter-spacing: -0.02em;">هشت بهشت</span>
                        <span style="font-size: 9px; font-weight: 700; background: #eef2ff; color: #4f46e5; padding: 1px 6px; border-radius: 4px; border: 1px solid #c7d2fe;">کارفرما</span>
                    </div>
                </div>
            '))
            ->colors([
                'primary' => Color::Amber,
            ])
            ->font(
                'PeydaWebVF',
                url: asset('fonts/peyda/fontiran.css'),
                provider: \Filament\FontProviders\LocalFontProvider::class,
            )
            ->maxContentWidth(\Filament\Support\Enums\Width::Full)
            ->sidebarWidth('14rem')
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
            ->discoverResources(in: app_path('Filament/Client/Resources'), for: 'App\\Filament\\Client\\Resources')
            ->discoverPages(in: app_path('Filament/Client/Pages'), for: 'App\\Filament\\Client\\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Client/Widgets'), for: 'App\\Filament\\Client\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
