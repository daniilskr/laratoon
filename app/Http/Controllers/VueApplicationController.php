<?php

namespace App\Http\Controllers;

class VueApplicationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return view('vue.app');
    }
}
