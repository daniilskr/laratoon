<?php

namespace App\Models\Concerns;

trait BelongsToMorphOwner
{
    use Helpers\TypehintProxyThis;

    public function owner()
    {
        return $this->prxThis()->morphTo(__FUNCTION__);
    }
}
