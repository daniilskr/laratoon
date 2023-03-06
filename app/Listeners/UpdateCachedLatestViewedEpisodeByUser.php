<?php

namespace App\Listeners;

use App\Events\EpisodeViewedByUser;
use App\Models\CachedLatestViewedEpisodeByUser;

class UpdateCachedLatestViewedEpisodeByUser
{
    public function handle(EpisodeViewedByUser $event): void
    {
        CachedLatestViewedEpisodeByUser::updateCache(
            $event->user,
            $event->episode->getComicId(),
            $event->episode,
        );
    }
}
