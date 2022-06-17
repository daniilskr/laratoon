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
        $comment = new Comment($request->validated());
        $comment->user()->associate($request->user());
        $commentable->comments()->save($comment);

        return new CommentResource($comment);
    }

    public function show(Comment $comment)
    {
        return new CommentResource($comment);
    }
}
