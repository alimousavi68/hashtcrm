<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Notifications\ProjectNotification;
use Filament\Notifications\Notification as FilamentNotification;

class NotificationService
{
    /**
     * Send notification to a specific user.
     */
    public static function sendToUser(User $user, Project $project, string $title, string $message, string $category = 'projects'): void
    {
        // 1. Send via Laravel Notification (Database, Email, SMS, Telegram)
        $user->notify(new ProjectNotification($project, $title, $message, $category));

        // 2. Also send via Filament Database Notification with Category for header bell dropdown
        $icon = match ($category) {
            'financial' => 'heroicon-o-credit-card',
            'tickets' => 'heroicon-o-chat-bubble-left-right',
            'system' => 'heroicon-o-cpu-chip',
            default => 'heroicon-o-folder',
        };

        FilamentNotification::make()
            ->title($title)
            ->body($message)
            ->icon($icon)
            ->category($category)
            ->sendToDatabase($user);
    }

    /**
     * Send notification to all admin users.
     */
    public static function sendToAdmins(Project $project, string $title, string $message, string $category = 'projects'): void
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            static::sendToUser($admin, $project, $title, $message, $category);
        }
    }
}
