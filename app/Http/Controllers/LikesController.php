<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Likeable;
use Illuminate\Http\Request;

class LikesController extends Controller
{
    protected function makeLikeForCurrentUser(Request $request)
    {
        $like = new Like();
        $like->user()->associate($request->user());

        return $like;
    }

    public function store(Request $request, Likeable $likeable)
    {
        $likeable->likes()->save($this->makeLikeForCurrentUser($request));

        return response('ok');
    }

    public function destroy(Request $request, Likeable $likeable)
    {
        /** @var Like */
        $like = $likeable->likes()->whereBelongsTo($request->user())->firstOrFail();
        $like->delete();

        return response('ok');
    }
}
