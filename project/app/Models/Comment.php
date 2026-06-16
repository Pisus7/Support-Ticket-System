<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Comment extends Model
{
    protected $table = 'comments';

    /** @use HasFactory<CommentFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'comment_id',
        'ticket_id',
        'user_id',
        'content',
    ];

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
