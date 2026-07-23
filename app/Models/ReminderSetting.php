<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReminderSetting extends Model
{
    protected $fillable = [
        'event_type',
        'delay_hours',
        'max_reminders',
        'channels',
        'is_active',
        'message_template',
    ];

    protected $casts = [
        'channels' => 'array',
        'is_active' => 'boolean',
        'delay_hours' => 'integer',
        'max_reminders' => 'integer',
    ];
}
