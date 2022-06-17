<?php

namespace App\Models\Concerns\Helpers;

use Illuminate\Database\Eloquent\Model;

trait TypehintProxyThis
{
    /**
     * Возвращение тайпхинта.
     */
    public function prxThis(): Model
    {
        return $this;
    }
}
