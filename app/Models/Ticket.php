<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'project_id',
        'client_id',
        'subject',
        'status',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }
}
