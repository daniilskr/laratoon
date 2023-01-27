<?php

namespace App\Http\Controllers;

use App\Http\Resources\ComicCardResource;
use App\Http\Resources\ImageResource;
use App\Models\Comic;
use App\Models\ComicHeaderBackground;
use Illuminate\Database\Eloquent\Collection;

class HomeComicCardsSectionsController extends Controller
{
    private const TYPE_GO_TO_MORE = 'go_to_more';

    private const TYPE_SMALL_WIDE_CARDS = 'small_wide_cards';

    public function __invoke()
    {
        /** @var Collection */
        $posters = ComicHeaderBackground::with('image')->limit(3)->get();

        return [
            [
                'type' => self::TYPE_SMALL_WIDE_CARDS,
                'title' => 'Recommended',
                'sectionPoster' => new ImageResource($posters->shift()->image),
                'comicCards' => ComicCardResource::collection(Comic::limit(6)->get()),
            ],
            [
                'type' => self::TYPE_GO_TO_MORE,
                'title' => 'Fantasy',
                'sectionPoster' => new ImageResource($posters->shift()->image),
                'comicCards' => ComicCardResource::collection(Comic::whereHas('genres', fn ($qG) => $qG->whereSlug('fantasy'))->limit(4)->get()),
            ],
            [
                'type' => self::TYPE_GO_TO_MORE,
                'title' => 'Comedy',
                'sectionPoster' => new ImageResource($posters->shift()->image),
                'comicCards' => ComicCardResource::collection(Comic::whereHas('genres', fn ($qG) => $qG->whereSlug('comedy'))->limit(4)->get()),
            ],
        ];
    }
}
