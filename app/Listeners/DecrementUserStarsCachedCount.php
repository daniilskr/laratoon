<?php

namespace App\Listeners;

use App\Events\LikeDeleted;

class DecrementUserStarsCachedCount
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LikeDeleted $event): void
    {
        //
    }
}
