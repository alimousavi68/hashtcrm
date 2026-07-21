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
                \Filament\Launchpad\LaunchpadPlugin::make()
                    ->spaces([
                        \Filament\Launchpad\Launchpad\LaunchpadSpace::make('داشبورد')
                            ->icon('heroicon-o-home')
                            ->pages([
                                \Filament\Launchpad\Launchpad\LaunchpadPage::make('خلاصه وضعیت')
                                    ->icon('heroicon-o-home')
                                    ->sections([
                                        \Filament\Launchpad\Launchpad\TileGroup::make('آمار پروژه‌ها و تیکت‌ها')
                                            ->tiles([
                                                \Filament\Launchpad\Launchpad\Tile::make('پروژه‌های فعال')
                                                    ->kpi(fn () => \App\Models\Project::where('client_id', auth('client')->id())->where('status', '!=', 'completed')->count())
                                                    ->icon('heroicon-o-folder-open')
                                                    ->subtitle('تعداد کل: ' . (auth('client')->check() ? \App\Models\Project::where('client_id', auth('client')->id())->count() : 0))
                                                    ->page(\App\Filament\Client\Pages\Projects::class),
                                                \Filament\Launchpad\Launchpad\Tile::make('پروژه‌های پایان‌یافته')
                                                    ->kpi(fn () => \App\Models\Project::where('client_id', auth('client')->id())->where('status', 'completed')->count())
                                                    ->icon('heroicon-o-folder')
                                                    ->page(\App\Filament\Client\Pages\Projects::class),
                                                \Filament\Launchpad\Launchpad\Tile::make('تیکت‌های باز پشتیبانی')
                                                    ->kpi(fn () => \App\Models\Ticket::where('client_id', auth('client')->id())->where('status', 'open')->count())
                                                    ->icon('heroicon-o-chat-bubble-left-right')
                                                    ->trend(
                                                        (auth('client')->check() && \App\Models\Ticket::where('client_id', auth('client')->id())->where('status', 'open')->count() > 0) ? 'نیاز به پیگیری' : 'همه پاسخ داده شده',
                                                        (auth('client')->check() && \App\Models\Ticket::where('client_id', auth('client')->id())->where('status', 'open')->count() > 0) ? 'warning' : 'success'
                                                    )
                                                    ->page(\App\Filament\Client\Pages\Tickets::class),
                                            ]),
                                        \Filament\Launchpad\Launchpad\TileGroup::make('دسترسی سریع')
                                            ->tiles([
                                                \Filament\Launchpad\Launchpad\Tile::make('پروژه‌های من')
                                                    ->subtitle('پیگیری و مدیریت فازهای پروژه')
                                                    ->icon('heroicon-o-folder')
                                                    ->page(\App\Filament\Client\Pages\Projects::class),
                                                \Filament\Launchpad\Launchpad\Tile::make('پشتیبانی و تیکت‌ها')
                                                    ->subtitle('ارتباط مستقیم با کارشناسان فنی')
                                                    ->icon('heroicon-o-chat-bubble-left-right')
                                                    ->page(\App\Filament\Client\Pages\Tickets::class),
                                            ]),
                                    ]),
                            ]),
                    ]),
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
