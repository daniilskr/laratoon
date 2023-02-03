<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\MakesComicCardSections;
use App\Models\Comic;

class HomeComicCardsSectionsController extends Controller
{
    use MakesComicCardSections;

    public function __invoke()
    {
        return [
            $this->makeSmallWideCardsSection(Comic::orderByDesc('id'), 'Recommended'),
            $this->makeGoToMoreSection(Comic::whereHas('genres', fn ($qG) => $qG->whereSlug('fantasy')), 'Fantasy'),
            $this->makeGoToMoreSection(Comic::whereHas('genres', fn ($qG) => $qG->whereSlug('comedy')), 'Comedy'),
        ];
    }
}
