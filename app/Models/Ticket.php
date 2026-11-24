<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'screen_id',
        'row',
        'seat',
        'is_paid',
        'unque_code',
    ];

    protected function casts(): array
    {
        return [
            'is_paid' => 'boolean',
            'row' => 'integer',
            'seat' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function screening(): BelongsTo
    {
        return $this->belongsTo(Screening::class, 'screen_id');
    }
}