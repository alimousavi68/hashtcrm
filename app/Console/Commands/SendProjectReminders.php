<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendProjectReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send automated reminders for projects in pending phases (brief, contract, review).';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting project reminders check...');

        $settings = \App\Models\ReminderSetting::where('is_active', true)->get()->keyBy('event_type');

        if ($settings->isEmpty()) {
            $this->info('No active reminder settings found.');
            return;
        }

        $projects = \App\Models\Project::whereIn('status', ['draft', 'brief', 'proforma', 'contract', 'review'])
            ->with('client')
            ->get();

        foreach ($projects as $project) {
            $eventType = 'pending_' . $project->status;
            
            // For contract phase, we might wait for signature or payment. We treat both as 'pending_contract' here.
            
            if (!$settings->has($eventType)) {
                continue;
            }

            $setting = $settings->get($eventType);

            // Check max reminders
            if ($project->reminder_count >= $setting->max_reminders) {
                continue;
            }

            // Determine when the project entered this state or when the last reminder was sent
            $lastActionAt = $project->last_reminded_at ?? $project->updated_at;
            $hoursSinceLastAction = $lastActionAt->diffInHours(\Illuminate\Support\Carbon::now());

            if ($hoursSinceLastAction >= $setting->delay_hours) {
                $this->sendReminder($project, $setting);
            }
        }

        $this->info('Finished sending reminders.');
    }

    protected function sendReminder($project, $setting)
    {
        $this->info("Sending reminder to Project #{$project->id} for event {$setting->event_type}");

        $client = $project->client ?? \App\Models\User::find($project->client_id);
        
        if (!$client) return;

        $message = str_replace(
            ['{client_name}', '{project_title}', '{status_label}'],
            [$client->name ?? 'مشتری گرامی', $project->title, $project->getStatusLabel()],
            $setting->message_template ?? "پروژه شما با نام «{project_title}» در مرحله «{status_label}» منتظر اقدام شماست. لطفا به پنل کاربری مراجعه کنید."
        );

        // Here we could dispatch a custom Notification or directly use the Notification Service
        \App\Services\NotificationService::sendToClient(
            $client,
            $project,
            'یادآوری وضعیت پروژه',
            $message,
            'projects',
            $setting->channels // ['database', 'sms']
        );

        // Update project reminder stats
        $project->increment('reminder_count');
        $project->update(['last_reminded_at' => \Illuminate\Support\Carbon::now()]);
    }
}
