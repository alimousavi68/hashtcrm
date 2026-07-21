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
        'views_count',
        'responses_count',
    ];

    protected $casts = [
        'schema'          => 'array',
        'is_active'       => 'boolean',
        'wizard_mode'     => 'boolean',
        'views_count'     => 'integer',
        'responses_count' => 'integer',
    ];

    public function getQuestionsCountAttribute(): int
    {
        return count($this->schema ?? []);
    }

    public function getDynamicResponsesCountAttribute(): int
    {
        $realCount = BriefAnswer::whereNotNull('dynamic_answers')->count();
        return $realCount > 0 ? $realCount : ($this->responses_count ?? 0);
    }
}
