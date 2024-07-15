<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\LifeLog;
use App\Models\Family;

class FamilyController extends Controller
{

    public $character;
    public $parent;
    public $eve;
    public $lives = [];

    public function index()
    {
        $eves = LifeLog::with('leaderboard')
                        ->where('type', 'birth')
                        ->where('pos_x', '>', -190000000)
                        ->where('pos_x', '<', 5000000)
                        ->where('parent_id', 0)
                        ->select('id', 'character_id', 'player_hash', 'parent_id', 'family_type', 'timestamp')
                        ->with('name:character_id,name')
                        ->take(50)
                        ->orderBy('id', 'desc')
                        ->get();

        $day_period = Carbon::now()->subDays(2)->timestamp;
        $week_period = Carbon::now()->subDays(7)->timestamp;

        $eve_count_last_24_hours = LifeLog::with('leaderboard')
            ->where('type', 'birth')
            ->where('pos_x', '>', -190000000)
            ->where('pos_x', '<', 50000)
            ->where('timestamp', '>=', $day_period)
            ->where('parent_id', 0)
            ->select('id')
            ->count();

        $eve_count_last_7_days = LifeLog::with('leaderboard')
            ->where('type', 'birth')
            ->where('pos_x', '>', -190000000)
            ->where('pos_x', '<', 50000)
            ->where('timestamp', '>=', $week_period)
            ->where('parent_id', 0)
            ->select('id')
            ->count();

        return view('families.index', ['eves' => $eves, 'eve_count_day' => $eve_count_last_24_hours, 'eve_count_week' => $eve_count_last_7_days]);
    }

    public function view($character_id)
    {
        $members = LifeLog::where('character_id', $character_id)
                        ->where('type', 'birth')
                        ->select('character_id', 'parent_id')
                        ->with('children', 'name:character_id,name')
                        ->get();
        //dd($members);
        return view('families.view', ['members' => $members]);
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

        dd($this->lives);
    }

    public function syncFamilyRecords()
    {
        $lives = LifeLog::where('type', 'birth')
                        ->where('pos_x', '>', -190000000)
                        ->where('pos_x', '<', 5000000)
                        ->where('parent_id', '!=', 0)
                        ->where('timestamp', '<=', 1700179092)
                        ->where('timestamp', '>=', 1700030900)
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
    }

    public function selbSolution()
    {
        $lives = LifeLog::where('type', 'birth')
                        ->where('pos_x', '>', -190000000)
                        ->where('pos_x', '<', 5000000)
                        ->take(10000)
                        ->orderBy('id', 'desc')
                        ->get();

        $eves = [];

        foreach($lives as $life)
        {
            if($life->parent_id == 0)
            {
                $eves[$life->character_id] = $life->character_id;
            }else
            {
                if(array_key_exists($life->parent_id, $eves))
                {
                    $eves[$life->character_id] = $eves[$life->parent_id];
                }else{
                    //print $life->character_id.'<br>';
                }
            }
        }

        dd($eves);
    }

    public function getChildren($character_id)
    {
        $lives = LifeLog::where('character_id', $character_id)
                        ->where('type', 'birth')
                        ->select('character_id', 'parent_id')
                        ->with('children', 'name:character_id,name')
                        ->get()
                        ->toArray();

        dd($lives);
    }
}
