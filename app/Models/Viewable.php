<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $views_cached_count
 *
 * @method static Builder whereEpisodeIn(int[]|Episode[] $episodes)
 * @method static Builder whereEpisode(int|Episode $episode)
 */
class Viewable extends Model
{
    use HasFactory,
        Concerns\BelongsToMorphOwner;

    protected $attributes = [
        'views_cached_count' => 0,
    ];

    public function views(): HasMany
    {
        return $this->hasMany(View::class);
    }

    public function viewOfUser(?User $user = null)
    {
        return $this->hasOne(View::class)->whereUser($user ?? -1);
    }

    /**
     * Wrapper for $this->viewOfUser(request()->user()).
     */
    public function viewOfRequestUser()
    {
        return $this->viewOfUser(request()->user());
    }

    public function firstOrCreateViewForUser(User $user): View
    {
        return $this->views()->firstOrCreate(['user_id' => $user->id]);
    }

    public function scopeWhereEpisodeIn(Builder $query, $episodes): Builder
    {
        return $query->whereHasMorph('owner', Episode::class, fn ($qE) => whereKeyInRaw($qE, $episodes));
    }

    public function scopeWhereEpisode(Builder $query, int|Episode $episode): Builder
    {
        return $query->whereEpisodeIn($episode);
    }
}
