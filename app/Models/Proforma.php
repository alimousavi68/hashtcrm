<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proforma extends Model
{
    protected $fillable = [
        'project_id',
        'items',
        'total_amount',
        'discount',
        'tax',
        'final_amount',
        'notes',
        'is_approved_by_client',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'is_approved_by_client' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
