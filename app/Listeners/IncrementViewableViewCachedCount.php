<?php

namespace App\Listeners;

use App\Events\ViewCreated;

class IncrementViewableViewCachedCount
{
    /**
     * Handle the event.
     */
    public function handle(ViewCreated $event): void
    {
        $event->view->viewable->increment('views_cached_count');
    }
}
