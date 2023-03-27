<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait LoadsConstrainedRelationships
{
    /**
     * Gets a model that belongs to $user from already loaded $relation or makes a constrained query.
     */
    public function getModelForUserFromRelation(null|int|User $user, string $relation): mixed
    {
        if (is_null($user)) {
            return null;
        }

        /** @var Collection|HasMany */
        $models = $this->relationLoaded($relation)
                    ? $this->$relation
                    : $this->$relation();

        return $models->where('user_id', modelKey($user))->first();
    }
}
