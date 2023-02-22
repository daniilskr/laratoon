<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\EpisodeViewedByUser;
use App\Events\ViewCreated;
use App\Events\CommentCreated;
use App\Events\LikeCreated;
use App\Events\CommentDeleted;
use App\Events\LikeDeleted;
use App\Listeners\IncrementViewableViewCachedCount;
use App\Listeners\IncrementUserViewsCachedCount;
use App\Listeners\IncrementCommentableCommentsCachedCount;
use App\Listeners\DecrementCommentableCommentsCachedCount;
use App\Listeners\IncrementRootChildCommentsCachedCount;
use App\Listeners\DecrementRootChildCommentsCachedCount;
use App\Listeners\IncrementUserCommentsCachedCount;
use App\Listeners\DecrementUserCommentsCachedCount;
use App\Listeners\IncrementLikeableLikesCachedCount;
use App\Listeners\DecrementLikeableLikesCachedCount;
use App\Listeners\IncrementUserLikesCachedCount;
use App\Listeners\DecrementUserLikesCachedCount;
use App\Listeners\UpdateLatestViewedEpisodeByUser;
use App\Listeners\UpdateCachedLatestViewedEpisodeByUser;
use App\Listeners\IncrementUserStarsCachedCount;
use App\Listeners\DecrementUserStarsCachedCount;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        EpisodeViewedByUser::class => [
            UpdateLatestViewedEpisodeByUser::class,
            UpdateCachedLatestViewedEpisodeByUser::class,
        ],

        ViewCreated::class => [
            IncrementViewableViewCachedCount::class,
            IncrementUserViewsCachedCount::class,
        ],
        CommentCreated::class => [
            IncrementCommentableCommentsCachedCount::class,
            IncrementRootChildCommentsCachedCount::class,
            IncrementUserCommentsCachedCount::class,
        ],
        CommentDeleted::class => [
            DecrementCommentableCommentsCachedCount::class,
            DecrementRootChildCommentsCachedCount::class,
            DecrementUserCommentsCachedCount::class,
        ],
        LikeCreated::class => [
            IncrementLikeableLikesCachedCount::class,
            IncrementUserLikesCachedCount::class,
            IncrementUserStarsCachedCount::class,
        ],
        LikeDeleted::class => [
            DecrementLikeableLikesCachedCount::class,
            DecrementUserLikesCachedCount::class,
            DecrementUserStarsCachedCount::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
