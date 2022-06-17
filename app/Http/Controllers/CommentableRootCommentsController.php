<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Commentable;

class CommentableRootCommentsController extends Controller
{
    public function __invoke(Commentable $commentable)
    {
        $comments = $commentable->comments()
                                ->rootsOnly()
                                ->orderByDesc('id')
                                ->cursorPaginate(10);

        return CommentResource::collection($comments);
    }
}
