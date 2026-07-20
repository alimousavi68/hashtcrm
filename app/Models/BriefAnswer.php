<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BriefAnswer extends Model
{
    protected $fillable = [
        'project_id',
        'business_name',
        'business_description',
        'target_audience',
        'competitors',
        'design_style',
        'color_preferences',
        'features_required',
        'extra_notes',
    ];

    protected function casts(): array
    {
        return [
            'features_required' => 'array',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
