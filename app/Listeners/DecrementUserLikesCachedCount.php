<?php

namespace App\Listeners;

use App\Events\LikeDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
