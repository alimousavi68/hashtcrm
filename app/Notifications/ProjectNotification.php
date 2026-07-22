<?php

namespace App\Notifications;

use App\Models\Project;
use App\Channels\SmsChannel;
use App\Channels\TelegramChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectNotification extends Notification
{
    use Queueable;

    protected Project $project;
    protected string $title;
    protected string $message;
    protected string $category;

    /**
     * Create a new notification instance.
     */
    public function __construct(Project $project, string $title, string $message, string $category = 'projects')
    {
        $this->project = $project;
        $this->title = $title;
        $this->message = $message;
        $this->category = $category;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail', SmsChannel::class, TelegramChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->title)
            ->greeting("سلام {$notifiable->name}")
            ->line($this->message)
            ->line("عنوان پروژه: {$this->project->title}")
            ->action('مشاهده پروژه', route('filament.client.pages.projects'))
            ->line('ممنون از همکاری شما با مجموعه هشت.');
    }

    /**
     * Get the array representation of the notification for database (Filament compatible).
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $icon = match ($this->category) {
            'financial' => 'heroicon-o-credit-card',
            'tickets' => 'heroicon-o-chat-bubble-left-right',
            'system' => 'heroicon-o-cpu-chip',
            default => 'heroicon-o-folder',
        };

        return [
            'format' => 'filament',
            'duration' => 'persistent',
            'title' => $this->title,
            'body' => $this->message,
            'icon' => $icon,
            'iconColor' => 'primary',
            'status' => 'info',
            'category' => $this->category,
            'viewData' => [
                'category' => $this->category,
                'project_id' => $this->project->id,
            ],
            'actions' => [],
        ];
    }

    /**
     * Custom SMS format.
     */
    public function toSms(object $notifiable): string
    {
        return "{$notifiable->name} عزیز،\n{$this->message}\nپروژه: {$this->project->title}\nشرکت هشت";
    }

    /**
     * Custom Telegram format.
     */
    public function toTelegram(object $notifiable): string
    {
        return "🔔 *{$this->title}*\n\n{$this->message}\n*پروژه:* {$this->project->title}";
    }
}
