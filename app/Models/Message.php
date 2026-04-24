<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ticket;

class Message extends Model
{
    // Allow mass assignment
    protected $fillable = [
        'ticket_id',
        'content',
        'sender_type',
    ];

    // Relationship: A message belongs to a ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}