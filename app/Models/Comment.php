<?php

namespace App\Models;

use App\Models\Contracts\BelongsToAUser;
use App\Models\Contracts\HasLikeable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\CommentCreated;
use App\Events\CommentDeleted;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property ?Commentable $commentable
 * @property ?Comment $rootComment
 * @property ?Comment $parentComment
 * @method static Builder whereRoot(int|Comment $viewables)
 */
class Comment extends Model implements BelongsToAUser, HasLikeable
{
    use HasFactory,
        Concerns\BelongsToAUser,
        Concerns\HasLikeable;

    protected $dispatchesEvents = [
        'created' => CommentCreated::class,
        'deleted' => CommentDeleted::class,
    ];

    protected $attributes = [
        'root_child_comments_cached_count' => 0,
    ];

    protected $fillable = [
        'comment_text',
    ];

    /**
     * Makes new reply with all the associations.
     */
    public function newReply(User $user, array $attributes): self
    {
        $reply = $this->commentable->newComment($user, $attributes);

        $reply->rootComment()->associate($this->isRoot() ? $this : $this->rootComment);
        $reply->parentComment()->associate($this);

        return $reply;
    }

    /**
     * Makes new reply with all the associations and saves it into the database.
     */
    public function createReply(User $user, array $attributes): ?self
    {
        if (($reply = $this->newReply($user, $attributes))->save()) {
            return $reply;
        }

        return null;
    }

    public function commentable(): BelongsTo
    {
        return $this->belongsTo(Commentable::class);
    }

    public function rootComment(): BelongsTo
    {
        return $this->belongsTo(self::class, 'root_comment_id');
    }

    public function parentComment(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_comment_id');
    }

    public function isRoot()
    {
        return is_null($this->root_comment_id);
    }

    public function scopeRootsOnly(Builder $query): Builder
    {
        return $query->whereNull('root_comment_id');
    }

    public function scopeWhereRoot(Builder $query, self|int $root): Builder
    {
        return $query->where('root_comment_id', modelKey($root));
    }
}
