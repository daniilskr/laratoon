<?php

namespace App\Http\Controllers;

use App\Models\Likeable;
use Illuminate\Http\Request;

class LikesController extends Controller
{
    public function store(Request $request, Likeable $likeable)
    {
        $likeable->createLike($request->user());
    }

    public function destroy(Request $request, Likeable $likeable)
    {
        $likeable->deleteUserLike($request->user());
    }
}
