<?php

namespace App\Models\Concerns;

use App\Models\Image;

trait HasOneImage
{
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable')->withDefault();
    }
}
