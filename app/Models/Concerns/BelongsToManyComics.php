<?php

namespace App\Models\Concerns;

use App\Models\Comic;

trait BelongsToManyComics
{
    public function comics()
    {
        return $this->belongsToMany(Comic::class)->withTimestamps();
    }
}
