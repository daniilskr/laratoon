<?php

namespace App\Models;

use App\Models\Contracts\BelongsToAUser;
use App\Models\Contracts\HasViewable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use App\Events\ViewCreated;

/**
 * @property ?Viewable $viewable
 * @method static Builder whereViewableIn(int[]|Viewable[]|HasViewable[]|Collection|EloquentCollection $viewables)
 * @method static Builder episodesOfComic(int|Comic $comic)
 */
class View extends Model implements BelongsToAUser
{
    use HasFactory,
        Concerns\BelongsToAUser;

    protected $dispatchesEvents = [
        'created' => ViewCreated::class,
    ];

    protected $fillable = [
        'user_id',
        'viewable_id',
    ];

    public function viewable()
    {
        return $this->belongsTo(Viewable::class);
    }

    /**
     * @param int[]|Viewable[]|HasViewable[]|Collection|EloquentCollection $viewables
     */
    public function scopeWhereViewableIn(Builder $query, $viewables)
    {
        if ($viewables instanceof EloquentCollection
            && $viewables->every(fn ($v) => $v instanceof HasViewable)
        ) {
            $viewables = $viewables->loadMissing('viewable')->map(fn (HasViewable $hasViewable) => $hasViewable->viewable);
        }

        return $query->whereIntegerInRaw('viewable_id', modelKeys($viewables));
    }

    public function scopeEpisodesOfComic(Builder $query, int|Comic $comic)
    {
        return $query->whereHas('viewable', function (Builder $qV) use ($comic) {
            $qV->whereHasMorph('owner', [Episode::class], function (Builder $qO) use ($comic) {
                $qO->where('comic_id', modelKey($comic));
            });
        });
    }
}
