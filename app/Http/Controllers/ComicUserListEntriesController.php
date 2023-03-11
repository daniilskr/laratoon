<?php

namespace App\Http\Controllers;

use App\Http\Resources\ComicUserListEntryResource;
use App\Models\Comic;
use App\Models\ComicUserList;
use Illuminate\Http\Request;

class ComicUserListEntriesController extends Controller
{
    public function index(ComicUserList $comicUserList)
    {
        $entries = $comicUserList->comicUserListEntries()
                                ->orderByDesc('updated_at')
                                ->orderByDesc('id')
                                ->cursorPaginate(30);

        return ComicUserListEntryResource::collection($entries);
    }

    public function moveComic(Request $request, string $comicUserListSlug, Comic $comic)
    {
        /** @var ComicUserList */
        $comicUserList = ComicUserList::whereUser($request->user())
                                ->whereSlug($comicUserListSlug)
                                ->firstOrFail();

        $comicUserList->moveEntry(
            $request->user(),
            $comic,
        );
    }

    public function removeComic(Request $request, Comic $comic)
    {
        ComicUserList::removeEntry(
            $request->user(),
            $comic,
        );
    }
}
