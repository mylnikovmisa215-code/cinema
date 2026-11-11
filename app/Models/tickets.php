<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tickets extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'screen_id', 'row', 'seat', 'is_paid', 'unique_code'];

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function screen():BelongsTo{
        return $this->belongsTo(Screen::class);
    }
}
