<?php

namespace App\Filament\Widgets;

use App\Models\Feedback;
use App\Models\Payment;
use App\Models\Ticket;
use Filament\Widgets\Widget;
use Illuminate\Notifications\DatabaseNotification;

class ClientActivityFeedWidget extends Widget
{
    protected static ?int $sort = 3;
    protected string $view = 'filament.widgets.client-activity-feed-widget';
    protected int | string | array $columnSpan = [
        'default' => 1,
        'lg' => 1,
    ];

    public function getActivities(): array
    {
        $activities = collect();

        // 1. نوتیفیکیشن‌های سیستم
        DatabaseNotification::latest()->take(4)->get()->each(function ($notif) use (&$activities) {
            $activities->push([
                'type' => 'notification',
                'title' => $notif->data['title'] ?? 'رویداد سیستم',
                'description' => $notif->data['message'] ?? '',
                'icon' => 'heroicon-o-bell',
                'color' => 'bg-amber-500',
                'created_at' => $notif->created_at,
            ]);
        });

        // 2. آخرین تیکت‌های پشتیبانی
        Ticket::with(['client', 'project'])->latest()->take(4)->get()->each(function ($ticket) use (&$activities) {
            $activities->push([
                'type' => 'ticket',
                'title' => 'تیکت جدید: ' . $ticket->subject,
                'description' => 'پروژه: ' . ($ticket->project?->title ?? 'عمومی') . ' | مشتری: ' . ($ticket->client?->name ?? 'نامشخص'),
                'icon' => 'heroicon-o-chat-bubble-left-right',
                'color' => 'bg-blue-500',
                'created_at' => $ticket->created_at,
            ]);
        });

        // 3. فیش‌های واریزی اخیر
        Payment::with('project')->latest()->take(4)->get()->each(function ($payment) use (&$activities) {
            $statusText = $payment->verified_at ? 'تایید شده' : 'منتظر تایید';
            $activities->push([
                'type' => 'payment',
                'title' => 'فیش واریزی: ' . number_format($payment->amount) . ' تومان',
                'description' => 'پروژه: ' . ($payment->project?->title ?? '-') . ' (' . $statusText . ')',
                'icon' => 'heroicon-o-banknotes',
                'color' => $payment->verified_at ? 'bg-emerald-500' : 'bg-rose-500',
                'created_at' => $payment->created_at,
            ]);
        });

        // 4. بازخوردهای جدید روی دمو
        Feedback::with('project')->latest()->take(4)->get()->each(function ($feedback) use (&$activities) {
            $activities->push([
                'type' => 'feedback',
                'title' => 'ثبت بازخورد/نظرات دمو',
                'description' => 'پروژه: ' . ($feedback->project?->title ?? '-') . ' - ' . mb_substr($feedback->notes ?? '', 0, 40) . '...',
                'icon' => 'heroicon-o-chat-bubble-bottom-center-text',
                'color' => 'bg-purple-500',
                'created_at' => $feedback->created_at,
            ]);
        });

        // مرتب‌سازی بر اساس تازه‌ترین تاریخ
        return $activities
            ->sortByDesc('created_at')
            ->take(6)
            ->values()
            ->all();
    }
}
