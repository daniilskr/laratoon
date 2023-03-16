<?php

namespace App\Models;

use App\Models\Contracts\BelongsToAUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Events\LikeCreated;
use App\Events\LikeDeleted;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model implements BelongsToAUser
{
    use HasFactory,
        Concerns\BelongsToAUser;

    protected $dispatchesEvents = [
        'created' => LikeCreated::class,
        'deleted' => LikeDeleted::class,
    ];

    public function likeable(): BelongsTo
    {
        return $this->belongsTo(Likeable::class);
    }

    public function scopeWhereCommentOfUser(Builder $query, User|int $user): Builder
    {
        return $query->whereHas('likeable', function (Builder $qL) use ($user) {
            $qL->whereHasMorph('owner', [Comment::class], function (Builder $qC) use ($user) {
                $qC->whereUser(modelKey($user));
            });
        });
    }
}
