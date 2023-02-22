<?php

namespace App\Listeners;

use App\Events\CommentCreated;

class IncrementUserCommentsCachedCount
{
    /**
     * Handle the event.
     */
    public function handle(CommentCreated $event): void
    {
        $event->comment->user->increment('comments_cached_count');
    }
}
