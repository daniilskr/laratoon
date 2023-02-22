<?php

namespace App\Listeners;

use App\Events\LikeCreated;

class IncrementUserLikesCachedCount
{
    /**
     * Handle the event.
     */
    public function handle(LikeCreated $event): void
    {
        $event->like->user->increment('likes_cached_count');
    }
}
