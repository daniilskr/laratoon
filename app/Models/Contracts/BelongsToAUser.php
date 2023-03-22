<?php

namespace App\Models\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface BelongsToAUser
{
    public function user(): BelongsTo;

    public function scopeWhereUser(Builder $query, User|int $user): Builder;
}
