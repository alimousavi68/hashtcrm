<?php

namespace App\Observers;

use App\Models\Project;
use App\Notifications\ProjectNotification;

class ProjectObserver
{
    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        $client = $project->client;
        if (!$client) {
            return;
        }

        // If status has changed
        if ($project->isDirty('status')) {
            $oldStatus = $project->getOriginal('status');
            $newStatus = $project->status;

            $statusMessages = [
                'brief' => [
                    'title' => 'فعال‌سازی پرسشنامه بریف نیازمندی‌ها',
                    'message' => 'پرسشنامه بریف پروژه برای شما فعال شده است. لطفا اطلاعات دسترسی و ترجیحات خود را تکمیل نمایید.',
                ],
                'contract' => [
                    'title' => 'آماده‌سازی قرارداد همکاری',
                    'message' => 'قرارداد پروژه آماده گردیده است. لطفا آن را در پنل کاربری خود مطالعه و امضا فرمایید.',
                ],
                'in_progress' => [
                    'title' => 'آغاز فرآیند طراحی و توسعه',
                    'message' => 'امور مالی و قرارداد تایید گردید؛ پروژه شما رسما وارد فاز طراحی و توسعه شد.',
                ],
                'review' => [
                    'title' => 'دموی پروژه آماده بازنگری است',
                    'message' => 'دموی اولیه پروژه طراحی شده و آماده بازخورد شماست. لطفا در مهلت مقرر نظرات خود را ثبت کنید.',
                ],
                'ready_handover' => [
                    'title' => 'دموی پروژه تایید شد',
                    'message' => 'دموی پروژه به تایید رسید؛ سیستم در حال آماده‌سازی بسته تحویل نهایی می‌باشد.',
                ],
                'completed' => [
                    'title' => 'تحویل نهایی پروژه',
                    'message' => 'پروژه شما با موفقیت خاتمه یافت. با تشکر از اعتماد شما به مجموعه هشت.',
                ],
            ];

            if (isset($statusMessages[$newStatus])) {
                $client->notify(new ProjectNotification(
                    $project,
                    $statusMessages[$newStatus]['title'],
                    $statusMessages[$newStatus]['message']
                ));
            }
        }

        // If is_settled became true
        if ($project->isDirty('is_settled') && $project->is_settled) {
            $client->notify(new ProjectNotification(
                $project,
                'تسویه حساب کامل مالی',
                'تسویه حساب کامل مالی پروژه شما تایید شد. بسته تحویل نهایی فعال گردید.'
            ));
        }
    }
}
