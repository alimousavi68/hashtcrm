<?php

namespace App\Channels;

use App\Services\Sms\SmsManager;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class SmsChannel
{
    protected SmsManager $smsManager;

    public function __construct(SmsManager $smsManager)
    {
        $this->smsManager = $smsManager;
    }

    /**
     * Send the given notification via SMS.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toSms')) {
            return;
        }

        $smsData = $notification->toSms($notifiable);
        $phone = $notifiable->phone ?? null;

        if (!$phone) {
            return;
        }

        // Support both string messages and array with pattern payload
        if (is_array($smsData) && isset($smsData['pattern_code'])) {
            $this->smsManager->sendPattern(
                $phone,
                $smsData['pattern_code'],
                $smsData['values'] ?? []
            );
        } elseif (is_string($smsData)) {
            $this->smsManager->sendSMS($phone, $smsData);
        }
    }
}
