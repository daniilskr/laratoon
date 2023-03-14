<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static Builder whereUser(User|int $user)
 * @method static Builder whereUserNot(User|int $user)
 */
trait BelongsToAUser
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUserId(): int
    {
        return $this->user()->getParentKey();
    }

    public function scopeWhereUser(Builder $query, User|int $user)
    {
        return $query->where($this->qualifyColumn('user_id'), modelKey($user));
    }

    public function scopeWhereUserNot(Builder $query, User|int $user)
    {
        return $query->where($this->qualifyColumn('user_id'), '<>', modelKey($user));
    }
}
