<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BriefTemplate extends Model
{
    protected $fillable = [
        'name',
        'schema',
        'is_active',
        'is_default',
        'wizard_mode',
        'guide_notice',
        'views_count',
        'responses_count',
    ];

    protected $casts = [
        'schema'          => 'array',
        'is_active'       => 'boolean',
        'is_default'      => 'boolean',
        'wizard_mode'     => 'boolean',
        'views_count'     => 'integer',
        'responses_count' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (BriefTemplate $template) {
            if ($template->is_default) {
                static::where('id', '!=', $template->id)->update(['is_default' => false]);
            }
        });
    }

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
