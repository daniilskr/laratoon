<?php

namespace App\Services\Http;

use App\Http\Resources\ComicCardResource;
use App\Http\Resources\ImageResource;
use Illuminate\Database\Eloquent\Builder;

class ComicCardsSectionFactory
{
    protected function makeSection(Builder $query, int $limit, string $type, string $title): array
    {
        return [
            'type' => $type,
            'title' => $title,
            'comicCards' => ComicCardResource::collection($comics = $query->limit($limit)->get()),
            'sectionPoster' => new ImageResource($comics->first()->comicHeaderBackground->image),
        ];
    }

    public function makeSmallWideCardsSection(Builder $query, string $title): array
    {
        return $this->makeSection($query, 6, 'small_wide_cards', $title);
    }

    public function makeGoToMoreSection(Builder $query, string $title): array
    {
        return $this->makeSection($query, 4, 'go_to_more', $title);
    }

    public function makeHeadingSection(Builder $query, string $title): array
    {
        return $this->makeSection($query, 5, 'heading', $title);
    }
}
