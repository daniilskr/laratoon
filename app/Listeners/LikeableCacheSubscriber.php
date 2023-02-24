<?php

namespace App\Listeners;

use App\Events\LikeCreated;
use App\Events\LikeDeleted;
use Illuminate\Events\Dispatcher;

class LikeableCacheSubscriber
{
    public function decrementOrIncrementLikeableLikesCachedCount(LikeCreated|LikeDeleted $event): void
    {
        $action = match ($event::class) {
            LikeDeleted::class => 'decrement',
            LikeCreated::class => 'increment',
        };

        $event->like->likeable()->$action('likes_cached_count');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            LikeDeleted::class => 'decrementOrIncrementLikeableLikesCachedCount',
            LikeCreated::class => 'decrementOrIncrementLikeableLikesCachedCount',
        ];
    }
}
