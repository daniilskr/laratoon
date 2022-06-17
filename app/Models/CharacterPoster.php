<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacterPoster extends Model
{
    use HasFactory,
        Concerns\HasOneImage;

    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
