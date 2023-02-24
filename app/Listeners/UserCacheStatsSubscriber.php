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
        $action = match ($event::class) {
            CommentDeleted::class => 'decrement',
            CommentCreated::class => 'increment',
        };

        $event->comment->user->$action('comments_cached_count');
    }

    public function incrementUserViewsCachedCount(ViewCreated $event)
    {
        $event->view->user->increment('views_cached_count');
    }

    protected function decrementOrIncrementUserLikesCachedCount(LikeCreated|LikeDeleted $event): void
    {
        $action = match ($event::class) {
            LikeDeleted::class => 'decrement',
            LikeCreated::class => 'increment',
        };

        $event->like->user->$action('likes_cached_count');
    }

    protected function decrementOrIncrementUserStarsCachedCount(LikeCreated|LikeDeleted $event): void
    {
        $owner           = $event->like->likeable->owner;
        $isComment       = Comment::class === $owner::class;
        $isByTheSameUser = ($comment = $owner)->user_id === $event->like->user_id;

        if ($isComment && ! $isByTheSameUser) {
            $action = match ($event::class) {
                LikeDeleted::class => 'decrement',
                LikeCreated::class => 'increment',
            };

            $comment->user->$action('stars_cached_count');
        }
    }

    public function handleLikeCreatedOrDeleted(LikeCreated|LikeDeleted $event)
    {
        $this->decrementOrIncrementUserLikesCachedCount($event);
        $this->decrementOrIncrementUserStarsCachedCount($event);
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
