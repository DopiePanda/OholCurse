<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

use App\Models\MapLog;
use App\Models\GameObject;
use App\Models\GameLeaderboard;
use App\Models\LeaderboardRecord;

class LeaderboardController extends Controller
{
    public $title;
    public $results;
    public $limit = 10;

    public $start;
    public $end;

    public function __construct()
    {

    }

    public function index()
    {
        return view('leaderboards.index');
    }

    public function weekly()
    {
        $lists = GameLeaderboard::where('type', 'weekly')->where('enabled', 1)->orderBy('id', 'desc')->get();

        return view('leaderboards.weekly', ['lists' => $lists]);
    }

    public function allTime()
    {
        $results = LeaderboardRecord::with('leaderboard', 'lifeName', 'playerName', 'player', 'character')
                    ->select('game_leaderboard_id', DB::raw("(MAX(amount)) as amount"), 'character_id', 'leaderboard_id', 'timestamp')
                    ->where('game_leaderboard_id', '!=', null)
                    ->groupBy('game_leaderboard_id')
                    ->orderBy('game_leaderboard_id', 'desc')
                    ->get();

        /*$posts = Post::withCount(['votes', 'comments' => function (Builder $query) {
            $query->where('content', 'like', 'code%');
        }])->get();*/

        
        return view('leaderboards.all-time', ['results' => $results]);
    }

    public function getObjectLeaderboard($object_id)
    {
        $object = GameLeaderboard::with('object')->where('object_id', $object_id)->first();

        $start = Carbon::now('UTC')->subDays(7);
        $start = $start->setTimeFromTimeString('00:00:00');

        $end = Carbon::now('UTC')->subDays(1);
        $end = $end->setTimeFromTimeString('00:00:00');

        $this->results = MapLog::with(['name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name'])
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'character_id')
                        ->where('object_id', $object_id)
                        ->where('timestamp', '<=', $end->timestamp)
                        ->where('timestamp', '>=', $start->timestamp)
                        ->where('character_id', '!=', '-1')
                        ->groupBy('character_id')
                        ->orderBy('count', 'desc')
                        ->limit($object->limit ?? 10)
                        ->get();

        return view('leaderboards.list', ['object' => $object, 'results' => $this->results, 'start' => $start, 'end' => $end]);
    }

    public function getMultiObjectsLeaderboard($id)
    {
        $object = GameLeaderboard::find($id);
        $objects = json_decode($object->multi_objects);

        $start = Carbon::now('UTC')->subDays(7);
        $start = $start->setTimeFromTimeString('00:00:00');

        $end = Carbon::now('UTC')->subDays(1);
        $end = $end->setTimeFromTimeString('00:00:00');

        $this->results = MapLog::with(['name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name'])
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'character_id')
                        ->whereIn('object_id', $objects)
                        ->where('timestamp', '<=', $end->timestamp)
                        ->where('timestamp', '>=', $start->timestamp)
                        ->where('character_id', '!=', '-1')
                        ->groupBy('character_id')
                        ->orderBy('count', 'desc')
                        ->limit(10)
                        ->get();

        //dd($this->results);

        return view('leaderboards.list', ['object' => $object, 'results' => $this->results, 'start' => $start, 'end' => $end]);
    }
}
