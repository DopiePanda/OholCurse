<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use Log;
use DB;
use Carbon\Carbon;

use App\Models\Yumlog;
use App\Models\CurseLog;
use App\Models\FoodLog;
use App\Models\LifeLog;
use App\Models\LifeNameLog;
use App\Models\MapLog;
use App\Models\Leaderboard;
use App\Models\PlayerScore;

class TestController extends Controller
{
    private $curseOffset = 3;
    private $forgiveOffset = 180;

    public function isCurseCheck()
    {
        $timestamp = 1695265789;
        $hash = "09fd6ae0db4c6e0903f0f7de0d86d7d88cc696b";

        $curse = CurseLog::where('timestamp', '>=', ($timestamp - $this->curseOffset))
                        ->where('timestamp', '<=', ($timestamp + $this->curseOffset))
                        ->where('reciever_hash', $hash)
                        ->where('type', 'curse')
                        ->first();
        if($curse)
        {
            $forgive = CurseLog::where('timestamp', '<=', ($curse->timestamp + $this->forgiveOffset))
                            ->where('timestamp', '>=', $curse->timestamp)
                            ->where('reciever_hash', $hash)
                            ->where('player_hash', $curse->player_hash)
                            ->where('type', 'forgive')
                            ->count();

            if($forgive > 0)
            {
                print 'Is curse check';
            }else{
                print 'Not curse check';
            }

        }
    }

    public function moveScores()
    {
        $leaderboards = Leaderboard::all();

        try{
            // Begin database transaction
            DB::beginTransaction();

            foreach($leaderboards as $leaderboard)
            {
                DB::table('player_scores')->insert([
                    'leaderboard_id' => $leaderboard->leaderboard_id,
                    'gene_score' => $leaderboard->gene_score,
                    'curse_score' => $leaderboard->curse_score,
                ]);
            }

            // Commit the DB transaction
            DB::commit();

        } catch(\Exception $e) {
                
            // Rollback DB transaction
            DB::rollback();

            // Log exception message
            Log::error('Exception returned when moving leaderboard scores:');
            Log::error($e);
        }
    }

    public function getScores()
    {
        $scores = CurseLog::with('leaderboard_recieved')
                    ->where('type', 'score')
                    ->where('curse_score', '>', 0)
                    ->orderBy('curse_score', 'desc')
                    ->get();

        try{

            // Begin database transaction
            DB::beginTransaction();

            foreach($scores as $score)
            {

            PlayerScore::updateOrCreate(
                [
                    'leaderboard_id' => $score->leaderboard_recieved->leaderboard_id,
                ],
                [
                    'curse_score' => $score->curse_score,
                ]
            );

            }

            // Commit the DB transaction
            DB::commit();

        } catch(\Exception $e) {
                
            // Rollback DB transaction
            DB::rollback();

            // Log exception message
            Log::error('Exception returned when updating player curse score: ');
            Log::error($e);
        }
    }

    public function moveHashes()
    {
        $leaderboards = Leaderboard::all();

        try{
            // Begin database transaction
            DB::beginTransaction();

            foreach($leaderboards as $leaderboard)
            {
                PlayerScore::updateOrCreate(
                    [
                        'leaderboard_id' => $leaderboard->leaderboard_id,
                    ],
                    [
                        'player_hash' => $leaderboard->player_hash,
                    ]
                );
            }

            // Commit the DB transaction
            DB::commit();

        } catch(\Exception $e) {
                
            // Rollback DB transaction
            DB::rollback();

            // Log exception message
            Log::error('Exception returned when moving leaderboard player hashes:');
            Log::error($e);
        }
    }

    public function getFoodEaten(Request $request, $character_id)
    {
        $foods = FoodLog::where('character_id', $character_id)
                    ->with('object:id,name')
                    ->select('timestamp', 'object_id', DB::raw('COUNT(object_id) as count'))
                    ->groupBy('object_id')
                    ->orderBy('timestamp', 'asc')
                    ->get();

        if($foods)
        {
            dd($foods->toArray());
        }else
        {
            print 'Life not found';
        }
    }

    public function getObjectInteractions(Request $request, $object_id, $ghost = false)
    {
        $start = Carbon::now('UTC')->subDays(14);
        $start = $start->setTimeFromTimeString('00:00:00');

        $end = Carbon::now('UTC')->subDays(1);
        $end = $end->setTimeFromTimeString('00:00:00');

        $results = MapLog::with(['lives:character_id,age,timestamp', 'name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name,leaderboard_id'])
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'character_id')
                        ->where('object_id', $object_id)
                        ->where('timestamp', '<=', $end->timestamp)
                        ->where('timestamp', '>=', $start->timestamp)
                        ->where('character_id', '!=', '-1')
                        ->groupBy('character_id')
                        ->orderBy('count', 'desc')
                        ->take(10)
                        ->get();
        
        $data = [];
        
        foreach ($results as $result) 
        {
            $time_alive = ($result->lives[1]->timestamp - $result->lives[0]->timestamp);

                if($time_alive <= 3600 )
                {

                }
                else
                {
                    $data[] = $result;
                }
        }

        dd($data);
    }
}
