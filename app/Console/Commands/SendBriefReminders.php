<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendBriefReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brief:send-reminders';

    protected $description = 'Send SMS and Notification reminders for missing brief items.';

    public function handle()
    {
        $projects = \App\Models\Project::whereIn('status', ['contract', 'in_progress', 'review'])
            ->with(['client', 'briefAnswer'])
            ->get();

        foreach ($projects as $project) {
            $schema = $project->brief_schema ?? [];
            $answers = $project->briefAnswer ? $project->briefAnswer->dynamic_answers : [];
            $answers = $answers ?: [];

            $missingFields = [];

            foreach ($schema as $block) {
                $data = $block['data'] ?? [];
                $name = $data['name'] ?? null;
                $label = $data['label'] ?? null;

                if (!$name || !$label) continue;

                if (empty($answers[$name])) {
                    $missingFields[] = $label;
                }
            }

            if (count($missingFields) > 0) {
                if ($project->client) {
                    $project->client->notify(new \App\Notifications\ProjectNotification(
                        $project,
                        'یادآوری تکمیل اطلاعات بریف',
                        "برخی از اطلاعات بریف پروژه شما (مثل: " . implode('، ', array_slice($missingFields, 0, 3)) . ") هنوز تکمیل نشده است. لطفاً در اسرع وقت اقدام نمایید.",
                        'projects'
                    ));

                    // Send SMS logic
                    $this->info("Reminder sent for Project #{$project->id} to {$project->client->name} about missing fields: " . implode(', ', $missingFields));
                }
            }
        }

        $this->info('Brief reminders processed successfully.');
    }
}
