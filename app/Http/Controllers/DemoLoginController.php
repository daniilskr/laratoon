<?php

namespace App\Http\Controllers;

use App\Services\DemoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemoLoginController extends Controller
{
    public function login(Request $request, DemoService $demoService)
    {
        if (Auth::hasUser()) {
            return response()->json([
                'message' => 'already logged in',
            ], /* Conflict */ 409);
        }

        $demoUser = $demoService->getDemoUserToAuth();

        if (is_null($demoUser)) {
            return response()->json([
                'message' => 'no demo accounts left...',
            ], 500);
        }

        Auth::login($demoUser);
        $request->session()->regenerate();

        return response()->json([
            'message' => 'successfully logged in',
        ], 200);
    }
}
