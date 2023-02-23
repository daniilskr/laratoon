<?php

namespace App\Listeners;

use App\Events\CommentDeleted;

class DecrementUserCommentsCachedCount
{
    /**
     * Handle the event.
     */
    public function handle(CommentDeleted $event): void
    {
        $event->comment->user->decrement('comments_cached_count');
    }
}
