<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Screening extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'hall_id',
        'start_time',
        'price',
        'format',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'price' => 'integer',
        ];
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function hall(): BelongsTo
    {
        return $this->belongsTo(Hall::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'screen_id');
    }
}