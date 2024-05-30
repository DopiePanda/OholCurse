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

Route::post('/discord/search', function(Request $request) {
    $data = $request->input();
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

});

Route::post('/discord/bot', function(Request $request) {
    $data = $request->input();

    if($data['request_type'] == 'curse_name_search')
    {
        $results = Yumlog::with('leaderboard:player_hash,leaderboard_name,leaderboard_id')
            ->select('player_hash', 'curse_name')
            ->where('curse_name', 'like', '%'.$data['curse_name'].'%')
            ->groupBy('player_hash')
            ->orderBy('curse_name', 'desc')
            ->get();

        print_r(json_encode($results));
    }

    if($data['request_type'] == 'life_name_search_live')
    {
        $query = null;

        if(isset($data['first_name']) && isset($data['last_name']))
        {
            $query = [
                'name' => $data['first_name'].' '.$data['last_name'] ?? null,
                'filter_entries' => 1,
                'live' => true
            ];
        }
        else
        {
            $query = [
                'name' => $data['first_name'],
                'filter_entries' => 1,
                'live' => true
            ];
        }

        // Open HTTP connection and fetch data as JSON
        
        $connection = Http::timeout(300)
                        ->retry(3, 180)
                        ->withQueryParameters($query)
                        ->get('https://yum.selb.io/yumdb/api/v1/leaderboards');

        $results = $connection->json();

        $payload = [];

        foreach($results as $result)
        {
            if(isset($result["entries"]))
            {
                foreach($result["entries"] as $entry)
                {
                    if($entry["rel"] == "You")
                    {
                        $payload[] = $result;
                    }
                }
            }
        }

        return response()->json($payload);
    }

    if($data['request_type'] == 'life_name_search_cached')
    {
        $query = null;

        if(isset($data['first_name']) && isset($data['last_name']))
        {
            $name = $data['first_name'].' '.$data['last_name'] ??  null;

            $query = [
                'name' => $name,
                'filter_entries' => 1,
            ];

            //print_r($query);
        }
        else
        {
            $query = [
                'name' => $data['first_name'],
                'filter_entries' => 1,
            ];

            //print_r($query);
        }

        // Open HTTP connection and fetch data as JSON
        
        $connection = Http::timeout(300)
                        ->retry(3, 180)
                        ->withQueryParameters($query)
                        ->get('https://yum.selb.io/yumdb/api/v1/leaderboards');

        $results = $connection->json();

        $payload = [];

        foreach($results as $result)
        {
            if(isset($result["entries"]))
            {
                foreach($result["entries"] as $entry)
                {
                    if($entry["rel"] == "You")
                    {
                        $payload[] = $result;
                    }
                }
            }
        }

        return response()->json($payload);
    }
    
});