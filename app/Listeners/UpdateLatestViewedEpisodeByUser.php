<?php

namespace App\Listeners;

use App\Events\EpisodeViewedByUser;

class UpdateLatestViewedEpisodeByUser
{
    public function handle(EpisodeViewedByUser $event): void
    {
        $event->episode->markAsLatestViewedEpisodeByUser($event->user);
    }
}
