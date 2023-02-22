<?php

namespace App\Listeners;

use App\Events\LikeCreated;
use App\Models\Comment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class IncrementUserStarsCachedCount
{
    /**
     * Handle the event.
     */
    public function handle(LikeCreated $event): void
    {
        $owner = $event->like->likeable->owner;
        $isComment = $owner::class === Comment::class;
        $isByTheSameUser = ($comment = $owner)->user_id === $event->like->user_id;

        if ($isComment && ! $isByTheSameUser) {
            $comment->user->increment('stars_cached_count');
        }
    }
}
