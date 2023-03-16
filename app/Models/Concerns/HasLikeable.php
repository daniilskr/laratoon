<?php

namespace App\Models\Concerns;

use App\Models\Likeable;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasLikeable
{
    public function likeable(): MorphOne
    {
        return $this->morphOne(Likeable::class, 'owner');
    }

    protected static function bootHasLikeable(): void
    {
        static::created(function (self $owner) {
            $owner->likeable()->save(new Likeable());
        });
    }
}
