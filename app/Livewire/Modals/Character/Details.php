<?php

namespace App\Livewire\Modals\Character;

use LivewireUI\Modal\ModalComponent;
use DB;

use App\Models\LifeLog;
use App\Models\LeaderboardRecord;
use App\Models\CurseLog;
use App\Models\MapLog;

class Details extends ModalComponent
{

    public $life;
    public $death;

    public $curses;
    public $trusts;
    public $forgives;

    public $records;

    public $object;

    public $sprite;

    public function mount($character_id)
    {
        $this->life = LifeLog::where('character_id', $character_id)
                    ->where('type', 'birth')
                    ->with('leaderboard:leaderboard_id,leaderboard_name', 'name:character_id,name')
                    ->first();

        $this->death = LifeLog::where('character_id', $character_id)
                    ->where('type', 'death')
                    ->first();

        $this->curses = CurseLog::where('character_id', $character_id)
                                ->where('type', 'curse')
                                ->count();

        $this->trusts = CurseLog::where('character_id', $character_id)
                                ->where('type', 'trust')
                                ->count();

        $this->forgives = CurseLog::where('character_id', $character_id)
                                ->where('type', 'forgive')
                                ->count();

        $this->records = LeaderboardRecord::where('character_id', $character_id)
                                ->count();

        $this->object = MapLog::where('character_id', $character_id)
                                ->with('object')
                                ->where('object_id', '!=', 0)
                                ->select('object_id', DB::raw('SUM(object_id) as amount'))
                                ->groupBy('object_id')
                                ->orderBy('amount', 'desc')
                                ->first();

        $this->getCharacterSprite($this->life->gender, $this->death->age, $this->life->family_type);
    }

    public function render()
    {
        return view('livewire.modals.character.details');
    }

    public function getCharacterSprite($gender, $age, $race)
    {
        $url = "assets/characters/sprites/$race/$gender/";

        if ($age <= 3) 
        {
            $url .= "baby";
        }elseif ($age > 3 && $age <= 13) 
        {
            $url .= "child";
        }elseif ($age > 13 && $age <= 24) 
        {
            $url .= "young-adult";
        }elseif ($age > 24 && $age <= 40) 
        {
            $url .= "adult";
        }elseif ($age > 40) 
        {
            $url .= "elderly";
        }

        //return asset($url."/1.png");
        $this->sprite = $url."/1.png";
    }
}
