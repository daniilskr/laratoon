<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Likeable;
use Illuminate\Http\Request;

class LikesController extends Controller
{
    public function store(Request $request, Likeable $likeable)
    {
        $likeable->likes()->save(Like::newForUser($request->user()));

        return response('ok');
    }

    public function destroy(Request $request, Likeable $likeable)
    {
        /** @var Like */
        $like = $likeable->likes()->whereUser($request->user())->firstOrFail();
        $like->delete();

        return response('ok');
    }
}
