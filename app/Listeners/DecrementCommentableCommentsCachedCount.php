<?php

namespace App\Listeners;

use App\Events\CommentDeleted;

class DecrementCommentableCommentsCachedCount
{
    /**
     * Handle the event.
     */
    public function handle(CommentDeleted $event): void
    {
        $event->comment->commentable()->decrement('comments_cached_count');
    }
}
