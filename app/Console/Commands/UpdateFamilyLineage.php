<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Log;

use App\Models\LifeLog;
use App\Models\Family;

class UpdateFamilyLineage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-family-lineage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the family lineage based on the life logs';


    public $character;
    public $parent;
    public $eve;
    public $lives = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $start = Carbon::now('UTC')->subDays(1);
        $start->setTimeFromTimeString('08:00:00');

        $end = Carbon::now('UTC')->subDays(2);
        $end->setTimeFromTimeString('08:00:00');

        $time_start = microtime(true);
        Log::channel('sync')->info("Being family lineage update from ".$end->format('Y-m-d H:i'). " to ".$start->format('Y-m-d H:i'));

        $lives = LifeLog::where('type', 'birth')
                        ->where('pos_x', '>', -190000000)
                        ->where('pos_x', '<', 5000000)
                        ->where('parent_id', '!=', 0)
                        ->where('timestamp', '<=', $start->timestamp)
                        ->where('timestamp', '>=', $end->timestamp)
                        ->select('character_id', 'parent_id')
                        ->get();

        foreach($lives as $life)
        {
            $this->getEve($life->character_id);
        }

        // Begin database transaction
        DB::beginTransaction();

        foreach($this->lives as $life)
        {
            Family::updateOrCreate(
                [
                    'character_id' => $life["character_id"],
                ],
                [
                    'parent_id' => $life["parent_id"],
                    'eve_id' => $life["eve_id"],
                ]
            );
        }

        // Commit the DB transaction
        DB::commit();

        $time_end = microtime(true);
        $time = round(($time_end - $time_start), 3);
        Log::channel('sync')->info("Finished updating family lineage in $time, total record: ".count($this->lives));
    }

    public function getEve($character_id)
    {
        $this->character = LifeLog::where('character_id', $character_id)->where('type', 'birth')->first();
        $this->parent = $this->character->parent_id;
        $this->eve = null;

        array_push($this->lives, ['character_id' => $this->character->character_id, 'parent_id' => $this->parent]);

        // Begin database transaction
        DB::beginTransaction();

        while($this->eve == null)
        {
            $character = LifeLog::where('character_id', $this->parent)
                                ->where('type', 'birth')
                                ->select('character_id', 'parent_id')
                                ->first();

            if($character && $character->parent_id != 0)
            {
                $this->parent = $character->parent_id;
                array_push($this->lives, ['character_id' => $character->character_id, 'parent_id' => $this->parent]);
            }else
            {
                $this->eve = $character;
                array_push($this->lives, ['character_id' => $character->character_id, 'parent_id' => 0, 'eve_id' => 0]);
            }
        }

        // Commit the DB transaction
        DB::commit();

        $i = 0;

        while($i != count($this->lives))
        {
            if(!isset($this->lives[$i]['eve_id']))
            {
                $this->lives[$i]['eve_id'] = $this->eve->character_id;
            }
            
            $i++;
        }
    }
}
