<?php

namespace App\Models\Concerns;

use App\Models\Comic;

trait BelongsToManyComics
{
    use Helpers\TypehintProxyThis;

    public function comics()
    {
        return $this->prxThis()->belongsToMany(Comic::class)->withTimestamps();
    }
}
