<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use App\Models\Ticket;
use Filament\Widgets\Widget;

class RecentTicketsAndPaymentsWidget extends Widget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';
    protected string $view = 'filament.widgets.recent-tickets-and-payments-widget';

    public function getPendingPayments(): array
    {
        return Payment::with(['project.client'])
            ->whereNull('verified_at')
            ->latest()
            ->take(4)
            ->get()
            ->map(function (Payment $p) {
                return [
                    'id' => $p->id,
                    'project_id' => $p->project_id,
                    'project_title' => $p->project?->title ?? 'پروژه نامشخص',
                    'client_name' => $p->project?->client?->name ?? 'نامشخص',
                    'amount' => number_format($p->amount),
                    'created_jalali' => \App\Helpers\JalaliHelper::toJalali($p->created_at),
                ];
            })
            ->toArray();
    }

    public function getOpenTickets(): array
    {
        return Ticket::with(['project', 'client'])
            ->where('status', '!=', 'closed')
            ->latest()
            ->take(4)
            ->get()
            ->map(function (Ticket $t) {
                return [
                    'id' => $t->id,
                    'subject' => $t->subject,
                    'project_title' => $t->project?->title ?? 'پشتیبانی عمومی',
                    'client_name' => $t->client?->name ?? 'نامشخص',
                    'created_jalali' => \App\Helpers\JalaliHelper::toJalali($t->created_at),
                    'status_label' => match ($t->status) {
                        'open' => 'باز',
                        'in_progress' => 'در حال بررسی',
                        'answered' => 'پاسخ داده‌شده',
                        'replied' => 'پاسخ مشتری',
                        'closed' => 'بسته‌شده',
                        default => $t->status,
                    },
                ];
            })
            ->toArray();
    }
}
