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

    public $name;
    public $pronoun;

    public $curses;
    public $trusts;
    public $forgives;

    public $records;

    public $object;
    public $actions;
    public $activity;

    public $sprite;

    public function mount($character_id)
    {
        $this->life = LifeLog::where('character_id', $character_id)
                    ->where('type', 'birth')
                    ->with('leaderboard:leaderboard_id,leaderboard_name', 'name:character_id,name', 'parent')
                    ->first();

        $this->death = LifeLog::where('character_id', $character_id)
                    ->where('type', 'death')
                    ->first();
   
        if($this->life->name)
        {
            $name = ucwords(strtolower($this->life->name->name), ' ') ?? 'UNNAMED';
            $name = explode(' ', $name);
        }else
        {
            $name = ['UNNAMED', 'UNNAMED'];
        }
        

        $this->name = [
            'first' => $name[0],
            'last' => $name[1] ?? 'UNNAMED'
        ];

        if($this->life->gender == 'female')
        {
            $this->pronoun = [
                'she',
                'her'
            ];
        }else
        {
            $this->pronoun = [
                'he',
                'his'
            ];
        }
        
        $this->getInteractions($character_id);
        $this->getCharacterSprite($this->life->gender, $this->death->age, $this->life->family_type);
    }

    public function render()
    {
        return view('livewire.modals.character.details');
    }

    public function getInteractions($character_id)
    {
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
                                ->with('object:id,name')
                                ->where('object_id', '!=', 0)
                                ->select('object_id', DB::raw('COUNT(object_id) as amount'))
                                ->groupBy('object_id')
                                ->orderBy('amount', 'desc')
                                ->first();

        $this->actions = MapLog::where('character_id', $character_id)
                                ->where('object_id', '!=', 0)
                                ->count();

        if ($this->actions < 50)
        {
            $this->activity = 'not very';
        }elseif ($this->actions < 250)
        {
            $this->activity = 'fairly';
        }elseif ($this->actions < 500)
        {
            $this->activity = 'very';
        }elseif ($this->actions < 1000)
        {
            $this->activity = 'extremely';
        }elseif ($this->actions < 2500)
        {
            $this->activity = 'ridiculously';
        }else
        {
            $this->activity = 'preposterously';
        }
    }

    public function getCharacterSprite($gender, $age, $race)
    {
        $url = env("CHARACTER_URL")."/sprites/$race/$gender/";

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
