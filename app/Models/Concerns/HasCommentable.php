<?php

namespace App\Models\Concerns;

use App\Models\Commentable;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasCommentable
{
    use Helpers\TypehintProxyThis;

    public function commentable(): MorphOne
    {
        return $this->prxThis()->morphOne(Commentable::class, 'owner');
    }

    protected static function bootHasCommentable()
    {
        static::created(function (self $owner) {
            $owner->commentable()->save(new Commentable());
        });
    }
}
