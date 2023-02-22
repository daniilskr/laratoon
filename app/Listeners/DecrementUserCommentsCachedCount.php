<?php

namespace App\Listeners;

use App\Events\CommentDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
