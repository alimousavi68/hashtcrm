<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'client_id',
        'title',
        'status',
        'is_settled',
        'demo_url',
        'feedback_deadline',
    ];

    protected function casts(): array
    {
        return [
            'feedback_deadline' => 'datetime',
            'is_settled' => 'boolean',
        ];
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function briefAnswer()
    {
        return $this->hasOne(BriefAnswer::class);
    }

    public function credential()
    {
        return $this->hasOne(ProjectCredential::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function handover()
    {
        return $this->hasOne(Handover::class);
    }
}
