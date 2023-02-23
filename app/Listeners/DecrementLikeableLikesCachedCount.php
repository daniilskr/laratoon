<?php

namespace App\Listeners;

use App\Events\LikeDeleted;

class DecrementLikeableLikesCachedCount
{
    /**
     * Handle the event.
     */
    public function handle(LikeDeleted $event): void
    {
        $event->like->likeable()->decrement('likes_cached_count');
    }
}
