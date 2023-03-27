<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 */
class Likeable extends Model
{
    use HasFactory,
        Concerns\BelongsToMorphOwner,
        Concerns\LoadsConstrainedRelationships;

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

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function getUserLike(null|int|User $user): ?Like
    {
        return $this->getModelForUserFromRelation($user, 'likes');
    }
}
