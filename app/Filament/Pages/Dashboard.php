<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverview::class,
            \App\Filament\Widgets\AggregateProjectProgressWidget::class,
            \App\Filament\Widgets\SingleProjectProgressCardsWidget::class,
            \App\Filament\Widgets\RecentTicketsAndPaymentsWidget::class,
        ];
    }
}
