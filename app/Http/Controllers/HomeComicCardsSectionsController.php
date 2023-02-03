<?php

namespace App\Http\Controllers;

use App\Http\Resources\ComicCardResource;
use App\Http\Resources\ImageResource;
use App\Models\Comic;
use Illuminate\Database\Eloquent\Builder;

class HomeComicCardsSectionsController extends Controller
{
    protected function makeSection(Builder $query, int $limit, string $title)
    {
        return [
            'title' => $title,
            'comicCards' => ComicCardResource::collection($comics = $query->limit($limit)->get()),
            'sectionPoster' => new ImageResource($comics->first()->comicHeaderBackground->image),
        ];
    }

    protected function makeSmallWideCardsSection(Builder $query, string $title)
    {
        return array_merge($this->makeSection($query, 6, $title), [
            'type' => 'small_wide_cards',
        ]);
    }

    protected function makeGoToMoreSection(Builder $query, string $title)
    {
        return array_merge($this->makeSection($query, 4, $title), [
            'type' => 'go_to_more',
        ]);
    }

    public function __invoke()
    {
        return [
            $this->makeSmallWideCardsSection(Comic::orderByDesc('id'), 'Recommended'),
            $this->makeGoToMoreSection(Comic::whereHas('genres', fn ($qG) => $qG->whereSlug('fantasy')), 'Fantasy'),
            $this->makeGoToMoreSection(Comic::whereHas('genres', fn ($qG) => $qG->whereSlug('comedy')), 'Comedy'),
        ];
    }
}
