<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpClient\HttpClient;
use Goutte\Client;

use App\Models\Yumlog;
use App\Models\LifeNameLog;

class DiscordBotController extends Controller
{
    public function search(Request $request)
    {
        $data = $request->input();

        switch ($data['request_type']) 
        {
            case 'curse_name_search':
                $this->searchCurseNames($data);
                break;

            case 'life_name_search_cached':
                $this->searchLifeCached($data);
                break;

            case 'life_name_search_live':
                $this->searchLifeLive($data);
                break;
            
            default:
                # code...
                break;
        }

        if($data['request_type'] == 'curse_name_search')
        {

        }
    }

    public function searchCurseNames($data)
    {
        $results = Yumlog::with('leaderboard:player_hash,leaderboard_name,leaderboard_id')
            ->select('player_hash', 'curse_name')
            ->where('curse_name', 'like', '%'.$data['curse_name'].'%')
            ->groupBy('player_hash')
            ->orderBy('curse_name', 'desc')
            ->get();

        print_r(json_encode($results));
    }

    public function searchLifeCached($data)
    {
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
    }

    public function searchLifeLive($data)
    {
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
    }

    public function searchLifeNameLogs()
    {
        $lives = LifeNameLog::where('name', 'like', $data['first_name'].' '.$data['last_name'] ?? null)
            ->limit(10)
            ->get();
    }
}
