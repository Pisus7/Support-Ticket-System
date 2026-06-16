<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Category extends Model
{
    protected $table = 'categories';

    /** @use HasFactory<CategoryFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'category_id',
        'name',
        'description'
    ];

    protected $guarded = [];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
