<?php

use App\Http\Controllers\DemoLoginController;
use App\Http\Controllers\VueApplicationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/demo-login', [DemoLoginController::class, 'login']);

Route::fallback(VueApplicationController::class);
