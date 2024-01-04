<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

use App\Models\MapLog;
use App\Models\LifeLog;
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
                    ->where('ghost', 0)
                    ->groupBy('game_leaderboard_id')
                    ->orderBy('game_leaderboard_id', 'desc')
                    ->get();
        
        return view('leaderboards.all-time', ['results' => $results]);
    }

    public function allTimeGhost()
    {
        $results = LeaderboardRecord::with('leaderboard', 'lifeName', 'playerName', 'player', 'character')
                    ->select('game_leaderboard_id', DB::raw("(MAX(amount)) as amount"), 'character_id', 'leaderboard_id', 'timestamp')
                    ->where('game_leaderboard_id', '!=', null)
                    ->where('ghost', 1)
                    ->groupBy('game_leaderboard_id')
                    ->orderBy('game_leaderboard_id', 'desc')
                    ->get();
        
        return view('leaderboards.all-time-ghost', ['results' => $results]);
    }

    public function getObjectLeaderboard($object_id)
    {
        $object = GameLeaderboard::with('object')->where('object_id', $object_id)->first();

        $start = Carbon::now('UTC')->subDays(7);
        $start = $start->setTimeFromTimeString('00:00:00');

        $end = Carbon::now('UTC')->subDays(1);
        $end = $end->setTimeFromTimeString('00:00:00');

        $this->results = MapLog::with(['lives', 'name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name'])
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'character_id')
                        ->where('object_id', $object_id)
                        ->where('timestamp', '<=', $end->timestamp)
                        ->where('timestamp', '>=', $start->timestamp)
                        ->where('character_id', '!=', '-1')
                        ->groupBy('character_id')
                        ->orderBy('count', 'desc')
                        ->orderBy('character_id', 'asc')
                        ->limit($object->limit ?? 10)
                        ->get();

        return view('leaderboards.list', ['object' => $object, 'results' => $this->results, 'start' => $start, 'end' => $end]);
    }

    public function getMultiObjectsLeaderboard($id)
    {
        $object = GameLeaderboard::find($id);

        $start = Carbon::now('UTC')->subDays(7);
        $start = $start->setTimeFromTimeString('00:00:00');

        $end = Carbon::now('UTC')->subDays(1);
        $end = $end->setTimeFromTimeString('00:00:00');

        $this->results = MapLog::with(['lives', 'name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name'])
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'character_id')
                        ->whereIn('object_id', $object->multi_objects)
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

    public function getAllNormalLives()
    {
        $time_to = Carbon::now()->timestamp;
        $time_from = Carbon::now()->subDays(14)->timestamp;
        $object_id = 1263;

        //if($object->multi != 1)
        //{
            $results = MapLog::with(['lives:character_id,timestamp', 'name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name,leaderboard_id'])
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'character_id')
                        ->where('object_id', $object_id)
                        ->where('timestamp', '<=', $time_to)
                        ->where('timestamp', '>=', $time_from)
                        ->where('character_id', '!=', '-1')
                        ->groupBy('character_id')
                        ->orderBy('count', 'desc')
                        ->take(10)
                        ->get();

        /* }else
        {
            $results = MapLog::with(['lives:character_id,timestamp', 'name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name,leaderboard_id'])
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'character_id')
                        ->whereIn('object_id', json_decode($object->multi_objects))
                        ->where('timestamp', '<=', $time_to)
                        ->where('timestamp', '>=', $time_from)
                        ->where('character_id', '!=', '-1')
                        ->groupBy('character_id')
                        ->orderBy('count', 'desc')
                        ->take(10)
                        ->get();
        }*/

        foreach($results as $result)
        {
            if(count($result->lives) == 2)
            {
                
                $time_alive = ($result->lives[1]->timestamp - $result->lives[0]->timestamp);

                if($time_alive <= 3600 )
                {
                    $ghost = false;
                    $record = LeaderboardRecord::where('object_id', $object_id)
                                            ->where('ghost', 0)
                                            ->orderBy('amount', 'desc')
                                            ->first();

                    print $result->life->name->name.' was not a ghost with count '.$result->count.'<br/>';
                }
                else
                {
                    $ghost = true;
                    $record = LeaderboardRecord::where('object_id', $object_id)
                                            ->where('ghost', 1)
                                            ->orderBy('amount', 'desc')
                                            ->first();

                    print $result->life->name->name.' WAS A GHOST with count '.$result->count.'<br/>';
                }

                if($record == null || $result->count > $record->amount)
                {

                    print 'Leaderboard updated';
                    /*
                    LeaderboardRecord::create([
                        'game_leaderboard_id' => $object->id,
                        'ghost' => $ghost,
                        'object_id' => $object->object_id,
                        'multi' => $object->multi,
                        'multi_objects' => $object->multi_objects,
                        'leaderboard_id' => $result->life->leaderboard->leaderboard_id,
                        'character_id' => $result->name->character_id,
                        'amount' => $result->count,
                        'timestamp' => $result->life->timestamp,
                    ]);
                    */
                }
            }
        }
    }
}
