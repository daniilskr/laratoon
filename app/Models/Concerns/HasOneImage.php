<?php

namespace App\Models\Concerns;

use App\Models\Image;

trait HasOneImage
{
    use Helpers\TypehintProxyThis;

    public function image()
    {
        return $this->prxThis()->morphOne(Image::class, 'imageable')->withDefault();
    }
}
