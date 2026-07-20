<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = [
        'project_id',
        'notes',
        'status',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
