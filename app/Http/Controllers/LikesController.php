<?php

namespace App\Http\Controllers;

use App\Models\Likeable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LikesController extends Controller
{
    public function store(Request $request, Likeable $likeable)
    {
        $likeable->createLike($request->user());

        return response(status: Response::HTTP_CREATED);
    }

    public function destroy(Request $request, Likeable $likeable)
    {
        $likeable->deleteUserLike($request->user());
    }
}
