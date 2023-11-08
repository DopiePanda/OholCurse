<?php

namespace App\Livewire\Admin\Logs\Map;

use Livewire\Component;
use Carbon\Carbon;
use \DB;
use \Log;

use App\Models\LifeLog;
use App\Models\MapLog;
class Area extends Component
{

    public $character_start;
    public $object_id;
    public $offset_x;
    public $offset_y;
    public $radius_size;

    public $group;

    public $birth_x,$birth_y;
    public $x_min,$x_max;
    public $y_min,$y_max;

    public $results;

    public function mount()
    {
        $this->group = null;
    }

    public function render()
    {
        return view('livewire.admin.logs.map.area');
    }

    public function getResults()
    {
        $this->validate([
            'character_start' => 'required|numeric',
            'object_id' => 'required|numeric|min:0|max:5000',
            'offset_x' => 'required|numeric',
            'offset_y' => 'required|numeric',
            'radius_size' => 'required|numeric',
        ]);

    
        try {
            $character = LifeLog::where('character_id', $this->character_start)->where('type', 'birth')->first();
        } catch (\Throwable $th) {
            Log::error($th);
        }

        if($character)
        {
            $birth = explode(',', $character->location);
            $this->birth_x = $birth[0];
            $this->birth_y = $birth[1];

            if($this->offset_x > 0)
            {
                $offset_x_min = $this->birth_x+$this->offset_x;
                $offset_x_max = $this->birth_x+$this->offset_x;
                $this->x_min = $offset_x_min+$this->radius_size;;
                $this->x_max = $offset_x_max-$this->radius_size;
            }else
            {
                $offset_x_min = $this->birth_x+$this->offset_x;
                $offset_x_max = $this->birth_x+$this->offset_x;
                $this->x_min = $offset_x_min+$this->radius_size;
                $this->x_max = $offset_x_max-$this->radius_size;
            }

            if($this->offset_y >= 0)
            {
                $offset_y_min = $this->birth_y+$this->offset_y;
                $offset_y_max = $this->birth_y+$this->offset_y;
                $this->y_min = $offset_y_min+$this->radius_size;
                $this->y_max = $offset_y_max-$this->radius_size;
            }else
            {
                $offset_y_min = $this->birth_y+$this->offset_y;
                $offset_y_max = $this->birth_y+$this->offset_y;
                $this->y_min = $offset_y_min+$this->radius_size;
                $this->y_max = $offset_y_max-$this->radius_size;
            }

            if($this->group)
            {
                $this->results = MapLog::with('name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name')
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'object_id', 'character_id', 'pos_x', 'pos_y', 'timestamp')
                        ->where('object_id', $this->object_id)
                        ->where('pos_x', '<=', $this->x_min)
                        ->where('pos_x', '>=', $this->x_max)
                        ->where('pos_y', '<=', $this->y_min)
                        ->where('pos_y', '>=', $this->y_max)
                        ->orderBy('timestamp', 'desc')
                        ->groupBy('character_id')
                        ->get();
            }else
            {
                $this->results = MapLog::with('name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name')
                        ->select('object_id', 'character_id', 'pos_x', 'pos_y', 'timestamp')
                        ->where('object_id', $this->object_id)
                        ->where('pos_x', '<=', $this->x_min)
                        ->where('pos_x', '>=', $this->x_max)
                        ->where('pos_y', '<=', $this->y_min)
                        ->where('pos_y', '>=', $this->y_max)
                        ->orderBy('timestamp', 'desc')
                        ->groupBy('id')
                        ->get();
            }
            

            //dd($this->results);

            /*$this->results = MapLog::with('name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name')
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'object_id', 'character_id', 'pos_x', 'pos_y', 'timestamp')
                        ->where('object_id', $this->object_id)
                        ->where('pos_x', '>=', $this->x_min)
                        ->where('pos_x', '<=', $this->x_max)
                        ->where('pos_y', '>=', $this->y_min)
                        ->where('pos_y', '<=', $this->y_max)
                        ->groupBy('character_id')
                        ->orderBy('timestamp', 'desc')
                        ->get(); */
        }
    }

    public function getXOffsetCoords($coord)
    {
        return $coord-$this->offset_x;
    }

    public function getYOffsetCoords($coord)
    {
        return $coord+$this->offset_y;
    }

}
