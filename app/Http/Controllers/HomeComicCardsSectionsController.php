<?php

namespace App\Http\Controllers;

use App\Http\Resources\ComicCardResource;
use App\Models\Comic;

class HomeComicCardsSectionsController extends Controller
{
    private const TYPE_GO_TO_MORE = 'go_to_more';

    private const TYPE_SMALL_WIDE_CARDS = 'small_wide_cards';

    public function __invoke()
    {
        return [
            [
                'type' => self::TYPE_SMALL_WIDE_CARDS,
                'title' => 'Recommended',
                'comic_cards' => ComicCardResource::collection(Comic::limit(6)->get()),
            ],
            [
                'type' => self::TYPE_GO_TO_MORE,
                'title' => 'Fantasy',
                'comic_cards' => ComicCardResource::collection(Comic::whereHas('genres', fn ($qG) => $qG->whereSlug('fantasy'))->limit(4)->get()),
            ],
            [
                'type' => self::TYPE_GO_TO_MORE,
                'title' => 'Comedy',
                'comic_cards' => ComicCardResource::collection(Comic::whereHas('genres', fn ($qG) => $qG->whereSlug('comedy'))->limit(4)->get()),
            ],
        ];
    }
}
