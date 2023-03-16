<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphTo;

trait BelongsToMorphOwner
{
    public function owner(): MorphTo
    {
        return $this->morphTo(__FUNCTION__);
    }
}
