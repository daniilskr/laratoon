<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 */
class Commentable extends Model
{
    use HasFactory,
        Concerns\BelongsToMorphOwner;

    protected $attributes = [
        'comments_cached_count' => 0,
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
