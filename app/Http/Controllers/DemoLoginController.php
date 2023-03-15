<?php

namespace App\Http\Controllers;

use App\Services\Demo\DemoUsersPool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class DemoLoginController extends Controller
{
    public function login(Request $request, DemoUsersPool $pool)
    {
        if (Auth::hasUser()) {
            return response()->json([
                'message' => 'already logged in',
            ], SymfonyResponse::HTTP_CONFLICT);
        }

        $demoUser = $pool->take();

        if (is_null($demoUser)) {
            return response()->json([
                'message' => 'no demo accounts left...',
            ], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        Auth::login($demoUser);
        $request->session()->regenerate();

        return response()->json([
            'message' => 'successfully logged in',
        ], SymfonyResponse::HTTP_OK);
    }
}
