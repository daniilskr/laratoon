<?php

namespace App\Listeners;

use App\Events\CommentDeleted;

class DecrementRootChildCommentsCachedCount
{
    /**
     * Handle the event.
     */
    public function handle(CommentDeleted $event): void
    {
        if (! ($comment = $event->comment)->isRoot()) {
            $comment->rootComment()->decrement('root_child_comments_cached_count');
        }
    }
}
