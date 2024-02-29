<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Resources\GrieferProfileResource;
use App\Models\GrieferProfile;
use App\Http\Middleware\AuthenticateOnceWithBasicAuth;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(AuthenticateOnceWithBasicAuth::class)->get('/griefers/all', function () {
    return GrieferProfileResource::collection(GrieferProfile::select('player_hash', 'leaderboard_id')->get());
});