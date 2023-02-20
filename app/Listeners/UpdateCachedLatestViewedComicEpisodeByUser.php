<?php

namespace App\Listeners;

use App\Events\EpisodeViewedByUser;
use App\Models\CachedLatestViewedEpisodeByUser;

class UpdateCachedLatestViewedComicEpisodeByUser
{
    public function handle(EpisodeViewedByUser $event): void
    {
        CachedLatestViewedEpisodeByUser::updateCache(
            $event->user,
            $event->episode->comic_id,
            $event->episode,
        );
    }
}
