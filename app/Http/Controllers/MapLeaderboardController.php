<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

use App\Models\MapLog;

class MapLeaderboardController extends Controller
{
    
    public function index()
    {
        $start = Carbon::now()->timestamp;
        $end = Carbon::now()->subDays(2)->timestamp;

        $corns = MapLog::with('object', 'name', 'life.leaderboard')
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'character_id')
                        ->where('object_id', 1114)
                        ->where('timestamp', '<=', $start)
                        ->where('timestamp', '>=', $end)
                        ->groupBy('character_id')
                        ->orderBy('count', 'desc')
                        ->limit(10)
                        ->get();

        $walls = MapLog::with('object', 'name', 'life.leaderboard')
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'character_id')
                        ->where('object_id', 883)
                        ->where('timestamp', '<=', $start)
                        ->where('timestamp', '>=', $end)
                        ->groupBy('character_id')
                        ->orderBy('count', 'desc')
                        ->limit(10)
                        ->get();

        $dungs = MapLog::with('object', 'name', 'life.leaderboard')
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'character_id')
                        ->where('object_id', 899)
                        ->where('timestamp', '<=', $start)
                        ->where('timestamp', '>=', $end)
                        ->groupBy('character_id')
                        ->orderBy('count', 'desc')
                        ->limit(10)
                        ->get();

        $composts = MapLog::with('object', 'name', 'life.leaderboard')
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'character_id')
                        ->where('object_id', 790)
                        ->where('timestamp', '<=', $start)
                        ->where('timestamp', '>=', $end)
                        ->groupBy('character_id')
                        ->orderBy('count', 'desc')
                        ->limit(10)
                        ->get();

        $mouflons = MapLog::with('object', 'name', 'life.leaderboard')
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'character_id')
                        ->where('object_id', 2711)
                        ->where('timestamp', '<=', $start)
                        ->where('timestamp', '>=', $end)
                        ->groupBy('character_id')
                        ->orderBy('count', 'desc')
                        ->limit(10)
                        ->get();

        return view('leaderboards', [
            'corns' => $corns, 
            'walls' => $walls, 
            'dungs' => $dungs, 
            'composts' => $composts,
            'mouflons' => $mouflons,
        ]);
    }
}
