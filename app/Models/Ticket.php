<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Message;

class Ticket extends Model
{
    // Allow mass assignment
    protected $fillable = [
        'category_id',
        'customer_name',
        'email',
        'phone',
        'description',
        'ref',
        'status',
    ];

    // Relationship: A ticket belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship: A ticket has many messages
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}