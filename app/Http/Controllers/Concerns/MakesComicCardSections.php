<?php

namespace App\Http\Controllers\Concerns;

use App\Http\Resources\ComicCardResource;
use App\Http\Resources\ImageResource;
use Illuminate\Database\Eloquent\Builder;

trait MakesComicCardSections
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

    protected function makeHeadingSection(Builder $query, string $title)
    {
        return array_merge($this->makeSection($query, 5, $title), [
            'type' => 'heading',
        ]);
    }
}
