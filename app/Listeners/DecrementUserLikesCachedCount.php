<?php

namespace App\Listeners;

use App\Events\LikeDeleted;

class DecrementUserLikesCachedCount
{
    /**
     * Handle the event.
     */
    public function handle(LikeDeleted $event): void
    {
        $event->like->user->decrement('likes_cached_count');
    }
}
