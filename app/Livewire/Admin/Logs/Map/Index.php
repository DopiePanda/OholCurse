<?php

namespace App\Livewire\Admin\Logs\Map;

use \Livewire\Component;
use \Carbon\Carbon;
use \Illuminate\Support\Arr;
use \DB;

use \App\Models\MapLog;
use \App\Models\GameObject;
use \App\Models\LifeLog;

class Index extends Component
{
    public $objects;
    public $lives;

    public $selected_character;
    public $selected_object;

    public $search;
    public $results = [];

    public $object_id;
    public $date_from;
    public $date_to;
    public $limit = 1000;

    public $character_id;
    public $character_name;

    public $iteration = 0;

    protected $listeners = [
        'filterUpdated' => '$refresh',
    ];

    public function mount()
    {
        $this->date_from = Carbon::now()->subDays(14)->timestamp;
        $this->date_to = Carbon::now()->timestamp;

        $this->objects = GameObject::select('id', 'name')->orderBy('id', 'asc')->get();

        $this->selected_character = null;
        $this->selected_object = null;
        //$this->getMapLogs($this->object_id, $this->character_id, $this->date_from, $this->date_to);
    }

    public function render()
    {
        
        return view('livewire.admin.logs.map.index');
    }

    public function updatingSelectedObject()
    {
        $this->dispatch('objectSelected');
        $this->iteration++;
    }

    public function resetField($name)
    {
        if($name == 'character')
        {
            $this->selected_character = null;
        }
        if($name == 'object')
        {
            $this->selected_object = null;
        }

        $this->updateMapLogData();
    }

    public function getMapLogs($object_id, $character_id = null, $from, $to, $limit = 10)
    {
        $this->results = MapLog::with('name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name')
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'character_id', 'pos_x', 'pos_y', 'timestamp')
                        ->where('object_id', $object_id)
                        ->where('timestamp', '<=', $to)
                        ->where('timestamp', '>=', $from)
                        ->where('character_id', '!=', '-1')
                        ->groupBy('character_id')
                        ->orderBy('timestamp', 'desc')
                        ->limit($limit)
                        ->get();

    }

    public function getSingleMapLogs($object_id, $character_id = null, $from, $to, $limit = 10)
    {
        $this->results = MapLog::with('name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name')
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'object_id', 'character_id', 'pos_x', 'pos_y', 'timestamp')
                        ->where('object_id', $object_id)
                        ->where('timestamp', '<=', $to)
                        ->where('timestamp', '>=', $from)
                        ->where('character_id', '!=', '-1')
                        ->where('character_id', $character_id)
                        ->groupBy('character_id')
                        ->orderBy('timestamp', 'desc')
                        ->limit($limit)
                        ->get();

    }

    public function searchBCyharacterId()
    {
        $this->results = MapLog::with('name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name', 'object')
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'object_id', 'character_id', 'pos_x', 'pos_y', 'timestamp')
                        ->where('timestamp', '<=', $this->date_to)
                        ->where('timestamp', '>=', $this->date_from)
                        ->where('character_id', '!=', '-1')
                        ->where('character_id', $this->character_id)
                        ->groupBy('object_id')
                        ->orderBy('timestamp', 'desc')
                        ->limit($this->limit)
                        ->get();

        //dd($this->results);
        //$this->dispatch('filterUpdated');

    }

    public function setGameObject($object_id)
    {
        $this->selected_object = $object_id;
        //$this->getLivesForObject($object_id);
        $this->updateMapLogData();

        $this->dispatch('objectSelected');
        $this->iteration++;
    }

    public function setLifeId($character_id)
    {
        $this->selected_character = $character_id;
        $this->updateMapLogData();
    }

    public function updateMapLogData()
    {
        if($this->selected_object != null && $this->selected_character != null)
        {
            $this->results = MapLog::with('name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name', 'object')
                        ->select('object_id', 'character_id', 'pos_x', 'pos_y', 'timestamp')
                        ->where('timestamp', '<=', $this->date_to)
                        ->where('timestamp', '>=', $this->date_from)
                        ->where('character_id', '!=', '-1')
                        ->where('character_id', $this->selected_character)
                        ->where('object_id', $this->selected_object)
                        ->orderBy('timestamp', 'desc')
                        ->limit($this->limit)
                        ->get();
        }elseif($this->selected_object != null && $this->selected_character == null)
        {
            $this->results = MapLog::with('name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name', 'object')
                        ->select('object_id', 'character_id', 'pos_x', 'pos_y', 'timestamp')
                        ->where('timestamp', '<=', $this->date_to)
                        ->where('timestamp', '>=', $this->date_from)
                        ->where('character_id', '!=', '-1')
                        ->where('object_id', $this->selected_object)
                        ->orderBy('timestamp', 'desc')
                        ->limit($this->limit)
                        ->get();

        }elseif($this->selected_object == null && $this->selected_character != null)
        {
            $this->results = MapLog::with('name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name', 'object')
                        ->select('object_id', 'character_id', 'pos_x', 'pos_y', 'timestamp')
                        ->where('timestamp', '<=', $this->date_to)
                        ->where('timestamp', '>=', $this->date_from)
                        ->where('character_id', '!=', '-1')
                        ->where('character_id', $this->selected_character)
                        ->orderBy('timestamp', 'desc')
                        ->groupBy('timestamp')
                        ->limit($this->limit)
                        ->get();
                        
        }else
        {

        }
    }

    public function getLivesForObject($object_id)
    {
        $lives = MapLog::select('character_id')
                        ->where('object_id', $object_id)
                        ->where('timestamp', '<=', $this->date_to)
                        ->where('timestamp', '>=', $this->date_from)
                        ->where('character_id', '!=', '-1')
                        ->groupBy('character_id')
                        ->get();


        //dd(Arr::flatten($lives->toArray()));
        //$this->lives = LifeLog::whereIn('character_id', $lives)->get();
        $this->lives = LifeLog::with('name:character_id,name', 'leaderboard:player_hash,leaderboard_name')
                        ->select('character_id', 'timestamp', 'player_hash')
                        ->where('timestamp', '<=', $this->date_to)
                        ->where('timestamp', '>=', $this->date_from)
                        ->where('character_id', '!=', '-1')
                        ->whereIn('character_id', Arr::flatten($lives->toArray()))
                        ->groupBy('character_id')
                        ->orderBy('timestamp', 'desc')
                        ->limit($this->limit)
                        ->get();
        //dd($this->lives);
    }
}
