<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    /**
     * The attributes that are mass assignable.
     * Including status and ref as they are used in your system logic.
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
     * Relationship: A ticket has many comments.
     * This prevents the 'foreach() argument must be of type array|object, null given' error
     * by returning an empty collection instead of null if no comments exist.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relationship: A ticket belongs to a category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}