<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCommentResource;
use App\Models\User;

class UserCommentsController extends Controller
{
    public function __invoke(User $user)
    {
        $comments = $user->comments()->orderByDesc('id')->cursorPaginate(10);

        return UserCommentResource::collection($comments);
    }
}
