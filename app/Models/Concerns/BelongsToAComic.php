<?php

namespace App\Models\Concerns;

use App\Models\Comic;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder whereComic(int|Comic $comic)
 */
trait BelongsToAComic
{
    use Helpers\TypehintProxyThis;

    public function comic()
    {
        return $this->prxThis()->belongsTo(Comic::class);
    }

    public function scopeWhereComic(Builder $query, int|Comic $comic)
    {
        return $query->where($this->prxThis()->qualifyColumn('comic_id'), ($comic instanceof Comic) ? $comic->id : $comic);
    }
}
