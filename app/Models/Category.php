<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ticket;

class Category extends Model
{
    // Allow mass assignment (important for create())
    protected $fillable = [
        'name',
    ];

    // Relationship: One category has many tickets
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}