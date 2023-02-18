<?php

namespace App\Listeners;

use App\Events\EpisodeViewedByUser;

class UpdateLatestViewedComicEpisode
{
    public function handle(EpisodeViewedByUser $event): void
    {
        $event->episode->makeLatestViewedComicEpisodeByUser($event->user);
    }
}
