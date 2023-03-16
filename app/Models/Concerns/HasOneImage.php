<?php

namespace App\Models\Concerns;

use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasOneImage
{
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->withDefault();
    }
}
