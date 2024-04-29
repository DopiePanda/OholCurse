<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Log;
use DB;

use App\Models\LifeLog;
use App\Models\GameLeaderboard;
use App\Models\MapLog;
use App\Models\LeaderboardRecord;


class UpdateLeaderboardRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-leaderboard-records {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start_time = microtime(true);

        $id = $this->argument('id');

        $start = Carbon::now('UTC')->subDays(14);
        $start = $start->setTimeFromTimeString('00:00:00');

        $end = Carbon::now('UTC')->subDays(0);
        $end = $end->setTimeFromTimeString('00:00:00');

        if($id)
        {
            $object = GameLeaderboard::find($id);
            $this->updateLeaderboardRecord($object, $start->timestamp, $end->timestamp);

            $end_time = microtime(true);
            $time = round(($end_time - $start_time), 3);

            Log::channel('sync')->info("Leaderboard Record For Item $object->object_id Updated in $time seconds");
        }else
        {
            $objects = GameLeaderboard::all();
            foreach($objects as $object)
            {
                $this->updateLeaderboardRecord($object, $start->timestamp, $end->timestamp);
            }

            $end_time = microtime(true);
            $time = round(($end_time - $start_time), 3);

            Log::channel('sync')->info("All Leaderboard Records Updated in $time seconds");
        }

    }

    public function updateLeaderboardRecord($object, $time_from, $time_to)
    {
        if($object->multi != 1)
        {
            $results = MapLog::with(['lives:character_id,timestamp', 'name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name,leaderboard_id'])
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'character_id')
                        ->where('object_id', $object->object_id)
                        ->where('timestamp', '<=', $time_to)
                        ->where('timestamp', '>=', $time_from)
                        ->where('character_id', '!=', '-1')
                        ->groupBy('character_id')
                        ->orderBy('count', 'desc')
                        ->take(10)
                        ->get();
        }else
        {
            $results = MapLog::with(['lives:character_id,timestamp', 'name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name,leaderboard_id'])
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'character_id')
                        ->whereIn('object_id', $object->multi_objects)
                        ->where('timestamp', '<=', $time_to)
                        ->where('timestamp', '>=', $time_from)
                        ->where('character_id', '!=', '-1')
                        ->groupBy('character_id')
                        ->orderBy('count', 'desc')
                        ->take(10)
                        ->get();
        }

        foreach($results as $result)
        {
            if(count($result->lives) == 2)
            {
                
                $time_alive = ($result->lives[1]->timestamp - $result->lives[0]->timestamp);

                if($time_alive <= 3660 )
                {
                    $ghost = 0;
                    $record = LeaderboardRecord::where('object_id', $object->object_id)
                                            ->where('ghost', 0)
                                            ->where('multi', $object->multi)
                                            ->orderBy('amount', 'desc')
                                            ->first();
                }
                else
                {
                    $ghost = 1;
                    $record = LeaderboardRecord::where('object_id', $object->object_id)
                                            ->where('ghost', 1)
                                            ->where('multi', $object->multi)
                                            ->orderBy('amount', 'desc')
                                            ->first();
                }

                if($record == null || $result->count > $record->amount && $record->ghost == $ghost)
                {

                    //dd($object);
                    
                    LeaderboardRecord::create([
                        'game_leaderboard_id' => $object->id,
                        'ghost' => $ghost,
                        'object_id' => $object->object_id,
                        'multi' => $object->multi,
                        'multi_objects' => json_encode($object->multi_objects) ?? null,
                        'leaderboard_id' => $result->life->leaderboard->leaderboard_id,
                        'character_id' => $result->name->character_id,
                        'amount' => $result->count,
                        'timestamp' => $result->life->timestamp,
                    ]);

                    Log::channel('sync')->info("New record set for object $object->object_id with amount $result->count");
                    
                }else
                {
                    //Log::channel('sync')->info("Record not updated for object $object->object_id. $result->count does not beat $record->amount");
                }
            }
        }
    }

    public function updateMultiLeaderboardRecord($objects, $time_from, $time_to)
    {
        $result = MapLog::with(['name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name,leaderboard_id'])
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'character_id')
                        ->whereIn('object_id', $objects)
                        ->where('timestamp', '<=', $time_to)
                        ->where('timestamp', '>=', $time_from)
                        ->where('character_id', '!=', '-1')
                        ->groupBy('character_id')
                        ->orderBy('count', 'desc')
                        ->first();

        $record = LeaderboardRecord::where('object_id', $object_id)->orderBy('amount', 'desc')->first();

        if($record == null || $result->count > $record->amount)
        {
            LeaderboardRecord::create([
                'object_id' => $object_id,
                'leaderboard_id' => $result->life->leaderboard->leaderboard_id,
                'character_id' => $result->name->character_id,
                'amount' => $result->count,
                'timestamp' => $result->life->timestamp,
            ]);
        }
    }
}
