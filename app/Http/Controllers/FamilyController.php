<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\LifeLog;
use App\Models\Family;

class FamilyController extends Controller
{

    public $character;
    public $parent;
    public $eve;
    public $lives = [];

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

        //dd($this->lives);
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
}
