<?php

namespace App\Models\Concerns;

use App\Models\Image;

trait HasManyImages
{
    use Helpers\TypehintProxyThis;

    public function images()
    {
        return $this->prxThis()->morphMany(Image::class, 'imageable');
    }
}
