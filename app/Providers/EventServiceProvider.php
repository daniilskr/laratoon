<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\EpisodeViewedByUser;
use App\Events\ViewCreated;
use App\Listeners\CommentableCacheSubscriber;
use App\Listeners\IncrementViewableViewCachedCount;
use App\Listeners\LikeableCacheSubscriber;
use App\Listeners\UserCacheStatsSubscriber;

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

        ViewCreated::class => [
            IncrementViewableViewCachedCount::class,
        ],

        EpisodeViewedByUser::class => [
            UpdateLatestViewedEpisodeByUser::class,
            UpdateCachedLatestViewedEpisodeByUser::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        UserCacheStatsSubscriber::class,
        LikeableCacheSubscriber::class,
        CommentableCacheSubscriber::class,
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
