<?php

namespace App\Http\Controllers;

use App\Http\Resources\ComicTagResource;
use App\Http\Resources\GenreResource;
use App\Http\Resources\PublicationStatusResource;
use App\Models\ComicTag;
use App\Models\Genre;
use App\Models\PublicationStatus;

class CatalogFiltersController extends Controller
{
    public function __invoke()
    {
        return [
            'genres' => GenreResource::collection(Genre::all()),
            'tags' => ComicTagResource::collection(ComicTag::all()),
            'statuses' => PublicationStatusResource::collection(PublicationStatus::all()),
        ];
    }
}
