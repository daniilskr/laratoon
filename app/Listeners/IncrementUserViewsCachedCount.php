<?php

namespace App\Listeners;

use App\Events\ViewCreated;

class IncrementUserViewsCachedCount
{
    /**
     * Handle the event.
     */
    public function handle(ViewCreated $event): void
    {
        $event->view->user->increment('views_cached_count');
    }
}
