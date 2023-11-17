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

        dd($this->lives);
    }

    public function syncFamilyRecords()
    {

    }

    public function moveLocationColoumn()
    {
        $take = 50000;
        $skip = 560000;

        $lives = LifeLog::where('id', '>', 0)
                        ->select('id', 'location')
                        ->take($take)
                        ->skip($skip)
                        ->get();

        // Begin database transaction
        DB::beginTransaction();

        foreach($lives as $life)
        {
            $pos = explode(',', $life->location);
            $life->pos_x = $pos[0];
            $life->pos_y = $pos[1];
            $life->save();
        }

        // Commit the DB transaction
        DB::commit();

        print "skipped $skip";
    }
}
