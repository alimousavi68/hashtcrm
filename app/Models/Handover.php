<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Handover extends Model
{
    protected $fillable = [
        'project_id',
        'congratulations_message',
        'training_videos',
        'final_credentials',
    ];

    protected function casts(): array
    {
        return [
            'training_videos' => 'array',
            'final_credentials' => 'encrypted',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
