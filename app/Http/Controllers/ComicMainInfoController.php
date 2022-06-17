<?php

namespace App\Http\Controllers;

use App\Http\Resources\ComicMainInfoResource;
use App\Models\Comic;

class ComicMainInfoController extends Controller
{
    public function __invoke(Comic $comic)
    {
        return new ComicMainInfoResource($comic);
    }
}
