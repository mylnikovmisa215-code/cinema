<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'duration',
        'year',
        'poster',
        'age_rating',
        'trailer_url',
    ];

    // Связь с жанрами
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'genre_movie');
    }

    // Связь с изображениями
    public function images(): HasMany
    {
        return $this->hasMany(MovieImage::class);
    }

    // Связь с сеансами
    public function screenings(): HasMany
    {
        return $this->hasMany(Screening::class);
    }
}