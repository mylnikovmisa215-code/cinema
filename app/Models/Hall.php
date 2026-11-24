<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hall extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_hall',
        'rows_count',
        'seats_count',
    ];

    public function screenings(): HasMany
    {
        return $this->hasMany(Screening::class);
    }
}