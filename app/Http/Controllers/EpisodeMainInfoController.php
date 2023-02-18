<?php

namespace App\Http\Controllers;

use App\Events\EpisodeViewedByUser;
use App\Http\Resources\EpisodeMainInfoResource;
use App\Models\Episode;

class EpisodeMainInfoController extends Controller
{
    public function __invoke(string $comicSlug, int $episodeNumber)
    {
        $episode = Episode::whereHas('comic', fn ($q) => $q->whereSlug($comicSlug))
                            ->whereNumber($episodeNumber)
                            ->firstOrFail();

        if ($user = request()->user()) {
            event(new EpisodeViewedByUser($episode, $user));
        }

        return new EpisodeMainInfoResource($episode);
    }
}
