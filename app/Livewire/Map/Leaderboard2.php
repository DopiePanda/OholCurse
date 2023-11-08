<?php

namespace App\Http\Livewire\Map;

use Livewire\Component;
use DB;
use Carbon\Carbon;
use Auth;

use App\Models\MapLog;
use App\Models\GameObject;

class Leaderboard2 extends Component
{

    public $time_start;

    public $objects;

    public $search;
    public $search_results;
    public $limit;

    public $start;
    public $end;

    public $results = [];
    public $tz;

    public function mount()
    {
        $this->time_start = microtime(true);
        $this->setLocalTimezone();
        $this->setTimeRange();
        $this->limit = session('limit', 10);   
        $this->getMapLeaderboard(session('object', 1114));
        $this->objects = GameObject::select('id', 'name')->orderBy('id', 'asc')->get();
    }

    public function render()
    {
        return view('livewire.map.leaderboard2');
    }

    public function setGameObject($object)
    {
        session(['object' => $object]);
        $this->getMapLeaderboard($object);
    }

    public function setResultLimit($limit)
    {
        $this->limit = $limit;
        session(['limit' => $limit]);
        $this->getMapLeaderboard(session('object', 1114));
    }

    public function getMapLeaderboard($object)
    {
        $this->time_start = microtime(true);
        $this->results = MapLog::with('name:character_id,name', 'life.leaderboard:player_hash,leaderboard_name')
                        ->select(DB::raw("(COUNT(object_id)) as count"), 'character_id')
                        ->where('object_id', $object)
                        ->where('timestamp', '<=', $this->end->timestamp)
                        ->where('timestamp', '>=', $this->start->timestamp)
                        ->where('character_id', '!=', '-1')
                        ->groupBy('character_id')
                        ->orderBy('count', 'desc')
                        ->limit($this->limit)
                        ->get();
    }

    public function setLocalTimezone()
    {
        $this->tz = Auth::user()->timezone ?? 'UTC';
    }
    
    public function setDateRange()
    {

        $this->end = Carbon::now()->subDay();
        $this->end->setHour(20);
        $this->end->setMinute(03);
        $this->end->setSecond(00);
        $this->end->tz($this->tz);

        $this->start = Carbon::now()->subDays(2);
        $this->start->setHour(20);
        $this->start->setMinute(03);
        $this->start->setSecond(00);
        $this->start->tz($this->tz);
    }

    public function setTimeRange()
    {
        $start = '23:59:59';
        $end   = '09:00:00';
        $now   = Carbon::now('UTC');
        $time  = $now->format('H:i:s');

        if ($time >= $start && $time <= $end) 
        {
            $this->end = Carbon::now()->subDays(2);
            $this->end = $this->end->setTimeFromTimeString('20:03:00');
            $this->end->tz($this->tz);

            $this->start = Carbon::now()->subDays(3);
            $this->start = $this->start->setTimeFromTimeString('20:03:00');
            $this->start->tz($this->tz);
        }else
        {
            $this->end = Carbon::now()->subDay();
            $this->end = $this->end->setTimeFromTimeString('20:03:00');
            $this->end->tz($this->tz);

            $this->start = Carbon::now()->subDays(2);
            $this->start = $this->start->setTimeFromTimeString('20:03:00');
            $this->start->tz($this->tz);
        }
    }

    public function redirectToProfile($life_id)
    {
        $life = LifeLog::where('character_id', $life_id)->first();
    }
}
