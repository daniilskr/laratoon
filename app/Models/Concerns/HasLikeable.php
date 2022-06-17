<?php

namespace App\Models\Concerns;

use App\Models\Likeable;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasLikeable
{
    use Helpers\TypehintProxyThis;

    public function likeable(): MorphOne
    {
        return $this->prxThis()->morphOne(Likeable::class, 'owner');
    }

    protected static function bootHasLikeable()
    {
        static::created(function (self $owner) {
            $owner->likeable()->save(new Likeable());
        });
    }
}
