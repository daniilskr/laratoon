<?php

namespace App\Models\Contracts;

use App\Models\Viewable;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property null|Viewable $viewable
 */
interface HasViewable
{
    public function viewable(): MorphOne;
}
