<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use App\Events\CommentDeleted;
use Illuminate\Events\Dispatcher;

class CommentableCacheSubscriber
{
    protected function decrementOrIncrementCommentableCommentsCachedCount(CommentCreated|CommentDeleted $event): void
    {
        $action = match ($event::class) {
            CommentDeleted::class => 'decrement',
            CommentCreated::class => 'increment',
        };

        $event->comment->commentable()->$action('comments_cached_count');
    }

    protected function decrementOrIncrementRootChildCommentsCachedCount(CommentCreated|CommentDeleted $event): void
    {
        if (! ($comment = $event->comment)->isRoot()) {
            $action = match ($event::class) {
                CommentDeleted::class => 'decrement',
                CommentCreated::class => 'increment',
            };

            $comment->rootComment()->$action('root_child_comments_cached_count');
        }
    }

    public function handleCommentCreatedOrDeleted(CommentCreated|CommentDeleted $event): void
    {
        $this->decrementOrIncrementCommentableCommentsCachedCount($event);
        $this->decrementOrIncrementRootChildCommentsCachedCount($event);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            CommentDeleted::class => 'handleCommentCreatedOrDeleted',
            CommentCreated::class => 'handleCommentCreatedOrDeleted',
        ];
    }
}
