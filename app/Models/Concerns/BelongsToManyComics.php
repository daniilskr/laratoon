<?php

namespace App\Models\Concerns;

use App\Models\Comic;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait BelongsToManyComics
{
    public function comics(): BelongsToMany
    {
        return $this->belongsToMany(Comic::class)->withTimestamps();
    }
}
