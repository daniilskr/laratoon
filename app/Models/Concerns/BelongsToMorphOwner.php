<?php

namespace App\Models\Concerns;

trait BelongsToMorphOwner
{
    public function owner()
    {
        return $this->morphTo(__FUNCTION__);
    }
}
