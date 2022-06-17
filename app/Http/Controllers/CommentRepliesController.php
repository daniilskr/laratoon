<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;

class CommentRepliesController extends Controller
{
    public function store(StoreCommentRequest $request, Comment $comment)
    {
        $reply = new Comment($request->validated());
        $root  = $comment->isRoot() ? $comment : $comment->rootComment;
        $reply->rootComment()->associate($root);
        $reply->parentComment()->associate($comment);
        $reply->user()->associate($request->user());

        $comment->commentable->comments()->save($reply);

        $root->increment('root_child_comments_cached_count');

        return new CommentResource($reply);
    }
}
