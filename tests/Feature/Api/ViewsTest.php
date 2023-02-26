<?php

namespace Tests\Feature\Api;

use App\Events\EpisodeViewedByUser;
use App\Models\CachedLatestViewedEpisodeByUser;
use App\Models\Comic;
use App\Models\Episode;
use App\Models\User;
use App\Models\View;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ViewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_user_view_updates_viewable_views_cached_count(): void
    {
        $viewable = $this->createEpisode()->viewable;
        $this->assertEquals(0, $viewable->views_cached_count);

        $viewable->firstOrCreateViewForUser($this->createUser());
        $this->assertEquals(1, $viewable->refresh()->views_cached_count);
    }

    public function test_creating_user_view_updates_user_views_cached_count(): void
    {
        $viewable = $this->createEpisode()->viewable;
        $this->assertEquals(0, ($user = $this->createUser())->views_cached_count);

        $viewable->firstOrCreateViewForUser($user);
        $this->assertEquals(1, $user->refresh()->views_cached_count);
    }

    public function test_getting_episode_main_info_dispatches_the_episode_viewed_by_user_event_when_authenticated(): void
    {
        Event::fake(EpisodeViewedByUser::class);

        $this->actingAs($user = $this->createUser());
        $this->getEpisodeMainInfo($episode = $this->createEpisode());

        Event::assertDispatched(
            fn (EpisodeViewedByUser $event) => $event->episode->is($episode) && $event->user->is($user)
        );
    }

    public function test_getting_episode_main_info_does_not_dispatch_the_episode_viewed_by_user_event_when_not_authenticated(): void
    {
        Event::fake(EpisodeViewedByUser::class);

        $this->getEpisodeMainInfo($this->createEpisode());

        Event::assertNotDispatched(EpisodeViewedByUser::class);
    }

    public function test_dispatching_the_episode_viewed_by_user_event_creates_cached_latest_viewed_episode_by_user(): void
    {
        $episode = $this->createEpisode();

        $this->actingAs($user = $this->createUser());

        $this->assertDatabaseCount(CachedLatestViewedEpisodeByUser::class, 0);

        Event::dispatch(new EpisodeViewedByUser($episode, $user));

        $this->assertDatabaseCount(CachedLatestViewedEpisodeByUser::class, 1);
        $this->assertDatabaseHas(
            CachedLatestViewedEpisodeByUser::class,
            [
                'user_id' => $user->id,
                'episode_id' => $episode->id,
                'comic_id' => $episode->comic->id,
            ],
        );
    }

    public function test_dispatching_the_episode_viewed_by_user_event_updates_existing_cached_latest_viewed_episode_by_user(): void
    {
        $episode = $this->createEpisode();

        $this->actingAs($user = $this->createUser());

        $this->assertDatabaseCount(CachedLatestViewedEpisodeByUser::class, 0);

        Event::dispatch(new EpisodeViewedByUser($episode, $user));
        $this->assertDatabaseCount(CachedLatestViewedEpisodeByUser::class, 1);
        $cached = CachedLatestViewedEpisodeByUser::first();

        Event::dispatch(new EpisodeViewedByUser($nextEpisode = $this->createNextEpisode($episode), $user));
        $this->assertDatabaseCount(CachedLatestViewedEpisodeByUser::class, 1);
        $this->assertDatabaseHas(
            CachedLatestViewedEpisodeByUser::class,
            [
                'id' => $cached->id,
                'user_id' => $user->id,
                'comic_id' => $nextEpisode->comic->id,
                'episode_id' => $nextEpisode->id,
            ],
        );
    }

    public function test_dispatching_the_episode_viewed_by_user_event_creates_a_view(): void
    {
        $episode = $this->createEpisode();

        $this->actingAs($user = $this->createUser());

        $this->assertDatabaseCount(View::class, 0);

        Event::dispatch(new EpisodeViewedByUser($episode, $user));

        $this->assertDatabaseHas(View::class, [
            'user_id' => $user->id,
            'viewable_id' => $episode->viewable->id,
        ]);
    }

    public function test_dispatching_the_episode_viewed_by_user_event_does_not_create_a_view_for_the_second_time_and_updates_timestamp_instead(): void
    {
        $episode = $this->createEpisode();

        $this->actingAs($user = $this->createUser());

        $this->assertDatabaseCount(View::class, 0);

        Carbon::setTestNow($firstTime = Carbon::now());
        Event::dispatch(new EpisodeViewedByUser($episode, $user));
        $this->assertDatabaseCount(View::class, 1);
        $view = View::first();

        Carbon::setTestNow($firstTime->addDay());
        Event::dispatch(new EpisodeViewedByUser($episode, $user));
        $this->assertDatabaseCount(View::class, 1);
        $this->assertNotEquals(
            $view->updated_at->toDateTimeString(),
            $view->refresh()->updated_at->toDateTimeString(),
        );
    }

    protected function getEpisodeMainInfo(Episode $episode): TestResponse
    {
        return $this->get(route(
            'comic_by_slug.episode_by_number.main_info',
            [
                'comicSlug' => $episode->comic->slug,
                'episodeNumber' => $episode->number,
            ],
        ));
    }

    protected function createNextEpisode(Episode $episode): Episode
    {
        return Episode::factory()->for($episode->comic)->create();
    }

    protected function createEpisode(): Episode
    {
        /** @var Comic */
        $comic = Comic::factory()
            ->has(Episode::factory(1))
            ->create();

        /* @var Episode */
        return $comic->episodes->first();
    }

    protected function createUser(): User
    {
        return User::factory()->create();
    }
}
