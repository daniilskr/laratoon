<?php

namespace App\Models;

use App\Models\Contracts\HasCommentable;
use App\Models\Contracts\HasLikeable;
use App\Models\Contracts\HasViewable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $full_name (max length 128)
 * @property string $description (text)
 */
class Character extends Model implements HasCommentable, HasLikeable, HasViewable
{
    use HasFactory,
        Concerns\HasCommentable,
        Concerns\HasViewable,
        Concerns\HasLikeable;

    public function characterRoles()
    {
        return $this->hasMany(CharacterRole::class);
    }

    public function comics()
    {
        return $this->belongsToMany(Comic::class, CharacterRole::class)->withTimestamps();
    }

    public function characterPoster()
    {
        return $this->hasOne(CharacterPoster::class);
    }
}
