<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Database\Factories\CategoryFactory;
use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Ticket extends Model
{
    protected $table = 'tickets';

    /** @use HasFactory<TicketFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'category_id',
        'ticket_nr',
        'ticket_subject',
        'ticket_message',
        'ticket_status'
    ];

    protected $guarded = [];

    protected $attributes = [
        'ticket_status' => TicketStatus::OPEN
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
