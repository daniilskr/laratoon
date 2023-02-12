<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;

class CommentRepliesController extends Controller
{
    public function store(StoreCommentRequest $request, Comment $comment)
    {
        return new CommentResource(Comment::createReply(
            $request->user(),
            $comment,
            $request->validated(),
        ));
    }
}
