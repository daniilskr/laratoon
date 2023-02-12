<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static Builder whereUser(User|int $user)
 */
trait BelongsToAUser
{
    use Helpers\TypehintProxyThis;

    public function user(): BelongsTo
    {
        return $this->prxThis()->belongsTo(User::class);
    }

    public function scopeWhereUser(Builder $query, User|int $user)
    {
        return $query->where($this->prxThis()->qualifyColumn('user_id'), ($user instanceof User) ? $user->id : $user);
    }
}
