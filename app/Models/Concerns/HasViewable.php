<?php

namespace App\Models\Concerns;

use App\Models\Viewable;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Contracts\HasViewable as HasViewableContract;

trait HasViewable
{
    public function viewable(): MorphOne
    {
        return $this->morphOne(Viewable::class, 'owner');
    }

    protected static function bootHasViewable()
    {
        static::created(function (HasViewableContract $owner) {
            $owner->viewable()->save(new Viewable());
        });
    }
}
