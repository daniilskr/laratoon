<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;

class CommentRepliesWithRootController extends Controller
{
    public function __invoke(Comment $root)
    {
        $replies = Comment::whereRoot($root)
                            ->orderByDesc('id')
                            ->cursorPaginate(5);

        return CommentResource::collection($replies);
    }
}
