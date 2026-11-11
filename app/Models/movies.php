<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class movies extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'duration', 'year', 'age_rating', 'poster', 'trailer_url'];
    
    public function images(): HasMany{

     |return $this->hasMany(MovieImages::class);

    }

    public function screens(): HasMany{
        
      |return $this->hasMany(Screen::class);

    }

    public function genres(): BelongsToMany{
        return $this->belongsToMany(Genre::class);
    }
}
