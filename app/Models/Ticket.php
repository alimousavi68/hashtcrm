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
        'is_read_by_admin',
        'is_read_by_client',
    ];

    protected $casts = [
        'is_read_by_admin' => 'boolean',
        'is_read_by_client' => 'boolean',
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
