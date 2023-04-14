<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComicsByCatalogFiltersRequest;
use App\Http\Resources\ComicCardResource;
use App\Queries\ComicsByCatalogFiltersQuery;

class CatalogEntriesController extends Controller
{
    public function __invoke(ComicsByCatalogFiltersRequest $request)
    {
        $comics = ComicsByCatalogFiltersQuery::newQuery($request->validated())
                        ->orderByDesc('id')
                        ->cursorPaginate(24);

        return ComicCardResource::collection($comics);
    }
}
