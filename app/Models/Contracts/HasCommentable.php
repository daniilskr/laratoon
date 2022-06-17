<?php

namespace App\Models\Contracts;

use App\Models\Commentable;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property null|Commentable $commentable
 */
interface HasCommentable
{
    public function commentable(): MorphOne;
}
