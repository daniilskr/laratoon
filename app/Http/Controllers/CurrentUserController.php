<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrentUserResource;
use Illuminate\Http\Request;

class CurrentUserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return new CurrentUserResource($request->user());
    }
}
