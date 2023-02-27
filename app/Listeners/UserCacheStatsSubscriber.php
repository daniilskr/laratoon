<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use App\Events\CommentDeleted;
use App\Events\LikeCreated;
use App\Events\LikeDeleted;
use App\Events\ViewCreated;
use App\Models\Comment;
use Illuminate\Events\Dispatcher;

class UserCacheStatsSubscriber
{
    public function decrementOrIncrementUserCommentsCachedCount(CommentCreated|CommentDeleted $event): void
    {
        $action = $this->getActionForEvent($event);

        $event->comment->user->$action('comments_cached_count');
    }

    public function incrementUserViewsCachedCount(ViewCreated $event): void
    {
        $event->view->user->increment('views_cached_count');
    }

    protected function decrementOrIncrementUserLikesCachedCount(LikeCreated|LikeDeleted $event): void
    {
        $action = $this->getActionForEvent($event);

        $event->like->user->$action('likes_cached_count');
    }

    protected function decrementOrIncrementUserStarsCachedCount(LikeCreated|LikeDeleted $event): void
    {
        $owner           = $event->like->likeable->owner;
        $isComment       = Comment::class === $owner::class;
        $isByTheSameUser = ($comment = $owner)->user_id === $event->like->user_id;

        if ($isComment && ! $isByTheSameUser) {
            $action = $this->getActionForEvent($event);

            $comment->user->$action('stars_cached_count');
        }
    }

    public function handleLikeCreatedOrDeleted(LikeCreated|LikeDeleted $event): void
    {
        $this->decrementOrIncrementUserLikesCachedCount($event);
        $this->decrementOrIncrementUserStarsCachedCount($event);
    }

    /**
     * @return 'decrement'|'increment'
     */
    protected function getActionForEvent(LikeCreated|LikeDeleted|CommentCreated|CommentDeleted $event): string
    {
        return match ($event::class) {
            LikeDeleted::class, CommentDeleted::class => 'decrement',
            LikeCreated::class, CommentCreated::class => 'increment',
        };
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            LikeDeleted::class => 'handleLikeCreatedOrDeleted',
            LikeCreated::class => 'handleLikeCreatedOrDeleted',

            CommentDeleted::class => 'decrementOrIncrementUserCommentsCachedCount',
            CommentCreated::class => 'decrementOrIncrementUserCommentsCachedCount',

            ViewCreated::class => 'incrementUserViewsCachedCount',
        ];
    }
}
