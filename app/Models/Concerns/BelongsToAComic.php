<?php

namespace App\Models\Concerns;

use App\Models\Comic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static Builder whereComic(int|Comic $comic)
 */
trait BelongsToAComic
{
    public function comic(): BelongsTo
    {
        return $this->belongsTo(Comic::class);
    }

    public function scopeWhereComic(Builder $query, int|Comic $comic)
    {
        return $query->where($this->qualifyColumn('comic_id'), ($comic instanceof Comic) ? $comic->id : $comic);
    }
}
