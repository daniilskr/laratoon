<?php

namespace App\Http\Controllers;

class VueApplicationController extends Controller
{
    public function __invoke()
    {
        return view('vue.app');
    }
}
