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
                        <span style="font-size: 9px; font-weight: 700; background: #eef2ff; color: #4f46e5; padding: 1px 6px; border-radius: 4px; border: 1px solid #c7d2fe;">CRM</span>
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
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn (): \Illuminate\Support\HtmlString => new \Illuminate\Support\HtmlString('<style>.fi-page-sub-navigation-sidebar-ctn { width: 13rem !important; flex-shrink: 0; }</style>')
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
            ->navigationGroups([
                \Filament\Navigation\NavigationGroup::make()
                    ->label('پشتیبانی و تیکت‌ها')
                    ->collapsed(false),
                \Filament\Navigation\NavigationGroup::make()
                    ->label('مدیریت سیستم'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\AggregateProjectProgressWidget::class,
                \App\Filament\Widgets\SingleProjectProgressCardsWidget::class,
                \App\Filament\Widgets\RecentTicketsAndPaymentsWidget::class,
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
