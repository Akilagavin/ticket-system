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
     * Accessor: Get the last agent who replied to this ticket.
     * Use $this->comments (Collection) to avoid extra database queries.
     */
    public function getLastCommentedAgentAttribute()
    {
        return $this->comments->sortByDesc('created_at')
            ->whereNotNull('user')
            ->pluck('user')
            ->first();
    }

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