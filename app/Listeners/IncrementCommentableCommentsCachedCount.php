<?php

namespace App\Listeners;

use App\Events\CommentCreated;

class IncrementCommentableCommentsCachedCount
{
    /**
     * Handle the event.
     */
    public function handle(CommentCreated $event): void
    {
        $event->comment->commentable()->increment('comments_cached_count');
    }
}
