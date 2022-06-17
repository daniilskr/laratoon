<?php

namespace App\Http\Controllers;

use App\Http\Resources\EpisodeResource;
use App\Models\Comic;

class ComicEpisodesController extends Controller
{
    public function index(Comic $comic)
    {
        return EpisodeResource::collection($comic->episodes()->get());
    }
}
