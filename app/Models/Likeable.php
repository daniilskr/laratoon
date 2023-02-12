<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 */
class Likeable extends Model
{
    use HasFactory,
        Concerns\BelongsToMorphOwner;

    protected $attributes = [
        'likes_cached_count' => 0,
    ];

    public function newLike(User $user): Like
    {
        $like = new Like();
        $like->user()->associate($user);
        $like->likeable()->associate($this);

        return $like;
    }

    public function createLike(User $user): ?Like
    {
        if (($like = $this->newLike($user))->save()) {
            return $like;
        }
        
        return null;
    }

    public function deleteUserLike(User $user): ?bool
    {
        return $this->likes()->whereUser($user)->firstOrFail()->delete();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function requestUserLike()
    {
        return $this->hasOne(Like::class)->where('user_id', request()->user()?->getKey() ?? -1);
    }
}
