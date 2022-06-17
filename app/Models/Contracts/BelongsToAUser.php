<?php

namespace App\Models\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

interface BelongsToAUser
{
    public function user(): BelongsTo;

    public function scopeWhereUser(Builder $query, User|int $user);
}
