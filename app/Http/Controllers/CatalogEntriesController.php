<?php

namespace App\Http\Controllers;

use App\Http\Resources\ComicCardResource;
use App\Models\Comic;
use Illuminate\Http\Request;

class CatalogEntriesController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = Comic::query();

        $query->when($request->input('tags'), function ($query, $tags) {
            whereHasAllUnique($query, 'comicTags', 'slug', collect($tags));
        });

        $query->when($request->input('genres'), function ($query, $genres) {
            whereHasAllUnique($query, 'genres', 'slug', collect($genres));
        });

        $query->when($request->input('statuses'), function ($query, $publicationStatuses) {
            whereHasIn($query, 'publicationStatus', 'slug', collect($publicationStatuses));
        });

        $query->when($request->input('year_from'), function ($query, string $yearFrom) {
            $query->whereYear('publishing_start', '>=', $yearFrom);
        });

        $query->when($request->input('year_to'), function ($query, string $yearTo) {
            $query->whereYear('publishing_end', '<=', $yearTo);
        });

        $comics = $query->orderByDesc('id')
                        ->cursorPaginate(24);

        return ComicCardResource::collection($comics);
    }
}
