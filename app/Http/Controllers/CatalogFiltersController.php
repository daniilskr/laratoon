<?php

namespace App\Http\Controllers;

use App\Models\ComicTag;
use App\Models\Genre;
use App\Models\PublicationStatus;

class CatalogFiltersController extends Controller
{
    public function __invoke()
    {
        return [
            'genres' => Genre::all()->map(fn ($genre) => [
                'id' => $genre->id,
                'name' => $genre->name,
                'slug' => $genre->slug,
            ]),
            'tags' => ComicTag::all()->map(fn ($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ]),
            'statuses' => PublicationStatus::all()->map(fn ($status) => [
                'id' => $status->id,
                'name' => $status->name,
                'slug' => $status->slug,
            ]),
        ];
    }
}
