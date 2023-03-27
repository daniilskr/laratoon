<?php

namespace App\Http\Controllers;

use App\Http\Resources\EpisodeMainInfoResource;
use App\Models\Episode;

class EpisodeMainInfoController extends Controller
{
    public function __invoke(string $comicSlug, int $episodeNumber)
    {
        /** @var Episode */
        $episode = Episode::whereComicSlug($comicSlug)
                            ->whereNumber($episodeNumber)
                            ->firstOrFail();

        if ($user = request()->user()) {
            $episode->dispatchViewedByUser($user);
        }

        return new EpisodeMainInfoResource($episode);
    }
}
