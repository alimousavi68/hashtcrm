<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BriefTemplate extends Model
{
    protected $fillable = [
        'name',
        'schema',
        'is_active',
        'wizard_mode',
        'guide_notice',
    ];

    protected $casts = [
        'schema'      => 'array',
        'is_active'   => 'boolean',
        'wizard_mode' => 'boolean',
    ];
}
