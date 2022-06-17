<?php

namespace App\Http\Controllers;

use App\Http\Resources\EpisodeMainInfoResource;
use App\Models\Episode;
use App\Services\ViewsService;

class EpisodeMainInfoController extends Controller
{
    public function __invoke(ViewsService $viewsService, string $comicSlug, int $episodeNumber)
    {
        $episode = Episode::whereHas('comic', fn ($q) => $q->whereSlug($comicSlug))
                            ->whereNumber($episodeNumber)
                            ->firstOrFail();

        if ($user = request()->user()) {
            $viewsService->updateLatestViewedComicEpisode($episode, $user);
        }

        return new EpisodeMainInfoResource($episode);
    }
}
