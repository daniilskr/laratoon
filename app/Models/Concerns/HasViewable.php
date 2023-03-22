<?php

namespace App\Models\Concerns;

use App\Models\Contracts\HasViewable as HasViewableContract;
use App\Models\Viewable;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasViewable
{
    public function viewable(): MorphOne
    {
        return $this->morphOne(Viewable::class, 'owner');
    }

    protected static function bootHasViewable(): void
    {
        static::created(function (HasViewableContract $owner) {
            $owner->viewable()->save(new Viewable());
        });
    }
}
