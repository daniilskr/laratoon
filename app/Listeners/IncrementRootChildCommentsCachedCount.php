<?php

namespace App\Listeners;

use App\Events\CommentCreated;

class IncrementRootChildCommentsCachedCount
{
    /**
     * Handle the event.
     */
    public function handle(CommentCreated $event): void
    {
        if (! ($comment = $event->comment)->isRoot()) {
            $comment->rootComment()->increment('root_child_comments_cached_count');
        }
    }
}
