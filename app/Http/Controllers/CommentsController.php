<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Commentable;

class CommentsController extends Controller
{
    public function store(StoreCommentRequest $request, Commentable $commentable)
    {
        return new CommentResource($commentable->createComment(
            $request->user(),
            $request->validated(),
        ));
    }
}
