<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $fillable = [
        'phone',
        'driver',
        'type',
        'pattern_code',
        'message',
        'status',
        'message_id',
        'error_message',
        'cost',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
    ];
}
