<?php

namespace App\Models\Concerns;

use App\Models\Commentable;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasCommentable
{
    public function commentable(): MorphOne
    {
        return $this->morphOne(Commentable::class, 'owner');
    }

    protected static function bootHasCommentable()
    {
        static::created(function (self $owner) {
            $owner->commentable()->save(new Commentable());
        });
    }
}
