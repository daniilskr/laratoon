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

    /**
     * Makes new comment with all the associations.
     */
    public function newComment(User $user, array $attributes): Comment
    {
        $comment = new Comment($attributes);
        $comment->user()->associate($user);
        $comment->commentable()->associate($this);

        return $comment;
    }

    /**
     * Makes new comment with all the associations and saves it into the database.
     */
    public function createComment(User $user, array $attributes): ?Comment
    {
        if (($comment = $this->newComment($user, $attributes))->save()) {
            return $comment;
        }

        return null;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
