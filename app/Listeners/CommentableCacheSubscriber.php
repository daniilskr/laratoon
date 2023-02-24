<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use App\Events\CommentDeleted;
use App\Models\Comment;
use Illuminate\Events\Dispatcher;

class CommentableCacheSubscriber
{
    protected function decrementOrIncrementCommentableCommentsCachedCount(Comment $comment, string $action): void
    {
        $comment->commentable()->$action('comments_cached_count');
    }

    protected function decrementOrIncrementRootChildCommentsCachedCount(Comment $comment, string $action): void
    {
        if (! $comment->isRoot()) {
            $comment->rootComment()->$action('root_child_comments_cached_count');
        }
    }

    public function handleCommentCreatedOrDeleted(CommentCreated|CommentDeleted $event): void
    {
        $action = match ($event::class) {
            CommentDeleted::class => 'decrement',
            CommentCreated::class => 'increment',
        };

        $this->decrementOrIncrementCommentableCommentsCachedCount($event->comment, $action);
        $this->decrementOrIncrementRootChildCommentsCachedCount($event->comment, $action);
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
