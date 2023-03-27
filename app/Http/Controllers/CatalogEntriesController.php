<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComicFiltersRequest;
use App\Http\Resources\ComicCardResource;
use App\Models\Comic;

class CatalogEntriesController extends Controller
{
    public function __invoke(ComicFiltersRequest $request)
    {
        $comics = Comic::queryWithFilters($request->getFilters())
                        ->orderByDesc('id')
                        ->cursorPaginate(24);

        return ComicCardResource::collection($comics);
    }
}
