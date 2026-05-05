<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The relationships that should always be eagerly loaded.
     * Including 'comments' and the nested 'comments.user' to fetch agent details.
     */
    protected $with = ['comments', 'comments.user'];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'category_id',
        'customer_name',
        'email',
        'phone',
        'description',
        'ref',
        'status',
    ];

    /**
     * Relationship: A Ticket Has Many Comments.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relationship: A Ticket Belongs To a Category.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}