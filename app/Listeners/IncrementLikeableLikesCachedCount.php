<?php

namespace App\Listeners;

use App\Events\LikeCreated;

class IncrementLikeableLikesCachedCount
{
    /**
     * Handle the event.
     */
    public function handle(LikeCreated $event): void
    {
        $event->like->likeable()->increment('likes_cached_count');
    }
}
