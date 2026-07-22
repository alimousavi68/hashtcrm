<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getColumns(): int | array
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\AggregateProjectProgressWidget::class,
            \App\Filament\Widgets\RevenueAndStatsWidget::class,
            \App\Filament\Widgets\SingleProjectProgressCardsWidget::class,
            \App\Filament\Widgets\RecentTicketsAndPaymentsWidget::class,
        ];
    }
}
