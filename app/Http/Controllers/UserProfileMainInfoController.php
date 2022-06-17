<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserProfileMainInfoResource;
use App\Models\User;

class UserProfileMainInfoController extends Controller
{
    public function __invoke(User $user)
    {
        return new UserProfileMainInfoResource($user);
    }
}
