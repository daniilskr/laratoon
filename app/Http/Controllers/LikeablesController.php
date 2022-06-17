<?php

namespace App\Http\Controllers;

use App\Models\Likeable;

class LikeablesController extends Controller
{
    public function show(Likeable $likeable)
    {
        return response()->json($likeable);
    }
}
