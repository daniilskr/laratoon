<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Services\Http\ComicCardsSectionFactory;

class HomeComicCardsSectionsController extends Controller
{
    public function __invoke(ComicCardsSectionFactory $comicCardsSectionFactory)
    {
        return [
            $comicCardsSectionFactory->makeHeadingSection(Comic::orderByDesc('id'), 'Latest updates'),
            $comicCardsSectionFactory->makeSmallWideCardsSection(Comic::orderByDesc('id'), 'Recommended'),
            $comicCardsSectionFactory->makeGoToMoreSection(Comic::whereHas('genres', fn ($qG) => $qG->whereSlug('fantasy')), 'Fantasy'),
            $comicCardsSectionFactory->makeGoToMoreSection(Comic::whereHas('genres', fn ($qG) => $qG->whereSlug('comedy')), 'Comedy'),
        ];
    }
}
