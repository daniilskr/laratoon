<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CharacterPoster extends Model
{
    use HasFactory,
        Concerns\HasOneImage;

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }
}
