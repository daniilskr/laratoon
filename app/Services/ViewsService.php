<?php

namespace App\Services;

use App\Models\CachedLatestViewedEpisodeByUser;
use App\Models\Comic;
use App\Models\View;
use App\Models\Viewable;
use App\Models\Episode;
use App\Models\User;

class ViewsService
{
    public function getTotalForComic(int|Comic $comic): int
    {
        if (is_int($comic)) {
            $episodeIds = Episode::whereComic($comic)->pluck('id');
        } else {
            $episodeIds = $comic->episodes()->pluck('id');
        }

        return (int) Viewable::whereEpisodeIn($episodeIds)->sum('views_cached_count');
    }

    public function addUserViewIfNone(Viewable $viewable, User $user): View
    {
        return $viewable->views()->firstOrCreate(['user_id' => $user->id]);
    }

    /**
     * What a mess... Maybe I should just denormalize 'views' table to optimize this...
     */
    public function getLatestViewedEpisodeByUser(User $user, Comic $comic): int
    {
        return View::episodesOfComic($comic)
                    ->whereUser($user)
                    ->latest('updated_at')
                    ->first()?->viewable?->owner?->id;
    }

    public function updateLatestViewedComicEpisode(Episode $episode, User $user): void
    {
        $latestView = $this->addUserViewIfNone($episode->viewable, $user);

        if (! $latestView->wasRecentlyCreated) {
            $latestView->touch();
        }

        CachedLatestViewedEpisodeByUser::updateOrCreate([
            'user_id' => $user->id,
            'comic_id' => $episode->comic_id,
        ], [
            'episode_id' => $episode->id,
        ]);
    }
}
