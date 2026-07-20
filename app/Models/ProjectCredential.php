<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectCredential extends Model
{
    protected $fillable = [
        'project_id',
        'host_provider',
        'host_username',
        'host_password',
        'host_panel_url',
        'domain_provider',
        'domain_username',
        'domain_password',
        'domain_panel_url',
        'admin_panel_url',
        'admin_username',
        'admin_password',
        'other_credentials',
    ];

    protected function casts(): array
    {
        return [
            'host_password' => 'encrypted',
            'domain_password' => 'encrypted',
            'admin_password' => 'encrypted',
            'other_credentials' => 'encrypted',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
