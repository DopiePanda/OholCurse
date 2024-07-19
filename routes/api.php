<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Resources\GrieferProfileResource;
use App\Models\GrieferProfile;
use App\Http\Middleware\AuthenticateOnceWithBasicAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpClient\HttpClient;
use Goutte\Client;

use App\Models\Yumlog;

use App\Http\Controllers\Api\DiscordBotController;
use App\Http\Controllers\Api\EllabotController;


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

Route::get('/yumlife', function(Request $request) {
    $data = $request->input();
    Log::debug("Recieved request:");
    Log::debug($data);
    print_r($data);
});

Route::prefix('/ellabot')->name('ellabot.')->group(function () {
    Route::post('/message', [EllabotController::class, 'message']);
});

Route::post('/discord/bot', function(Request $request) {
    $data = $request->input();
    
    if(isset($data['token']) && $data['token'] == env('DISCORD_WEBHOOK_TOKEN'))
    {
            
        $bot = new DiscordBotController();

        switch ($data['request_type']) 
        {
            case 'curse_name_search':
                return $bot->searchCurseNames($data);
                break;

            case 'life_name_search_cached':
                return $bot->searchLifeCached($data);
                break;

            case 'life_name_search_live':
                return $bot->searchLifeLive($data);
                break;
            
            default:
                # code...
                break;
        }

    }
    else
    {
        print 'Not authorized :)';
    }

});