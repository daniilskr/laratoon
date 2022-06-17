<?php

namespace App\Models\Contracts;

use App\Models\Likeable;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property null|Likeable $likeable
 */
interface HasLikeable
{
    public function likeable(): MorphOne;
}
