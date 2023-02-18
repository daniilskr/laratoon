<?php

namespace App\Listeners;

use App\Events\EpisodeViewedByUser;

class UpdateLatestViewedComicEpisode
{
    public function handle(EpisodeViewedByUser $event): void
    {
        $event->episode->markAsLatestViewedComicEpisodeByUser($event->user);
    }
}