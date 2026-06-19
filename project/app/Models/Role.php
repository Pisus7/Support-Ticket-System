<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Role extends Model
{
    protected $table = 'roles';

    /** @use HasFactory<TicketFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'description'
    ];

    protected $guarded = [];

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
