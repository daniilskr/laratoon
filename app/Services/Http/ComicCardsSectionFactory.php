<?php

namespace App\Services\Http;

use App\Http\Resources\ComicCardResource;
use App\Http\Resources\ImageResource;
use Illuminate\Database\Eloquent\Builder;

class ComicCardsSectionFactory
{
    protected function makeSection(Builder $query, int $limit, string $title)
    {
        return [
            'title' => $title,
            'comicCards' => ComicCardResource::collection($comics = $query->limit($limit)->get()),
            'sectionPoster' => new ImageResource($comics->first()->comicHeaderBackground->image),
        ];
    }

    public function makeSmallWideCardsSection(Builder $query, string $title)
    {
        return array_merge($this->makeSection($query, 6, $title), [
            'type' => 'small_wide_cards',
        ]);
    }

    public function makeGoToMoreSection(Builder $query, string $title)
    {
        return array_merge($this->makeSection($query, 4, $title), [
            'type' => 'go_to_more',
        ]);
    }

    public function makeHeadingSection(Builder $query, string $title)
    {
        return array_merge($this->makeSection($query, 5, $title), [
            'type' => 'heading',
        ]);
    }
}
