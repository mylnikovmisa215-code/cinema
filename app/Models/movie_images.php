<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class movie_images extends Model
{
    use HasFactory;

    protected $fillable = ['movie_id', 'image_path'];

    public function movie():BelongsTo{
        return $this->belongsTo(Movie::class);
    }
}
