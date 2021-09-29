<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotifyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['web'])->group(function () {
    Route::get('/spotify/auth', [SpotifyController::class, 'getAuthUrl']);
    Route::get('/spotify/callback', [SpotifyController::class, 'getTokens']);
});

Route::post('/spotify/currently_playing', [SpotifyController::class, 'getCurrentlyPlaying']);