<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BriefTemplate extends Model
{
    protected $fillable = [
        'name',
        'schema',
        'is_active',
    ];

    protected $casts = [
        'schema' => 'array',
        'is_active' => 'boolean',
    ];
}
