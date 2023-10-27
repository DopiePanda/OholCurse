<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;
use DB;

use App\Models\Report;
use App\Models\CurseLog;
use App\Models\Leaderboard;
use App\Models\LifeLog;
use App\Models\LifeNameLog;
use App\Models\Yumlog;

class Home extends Component
{

    public $query;

    public $filter;
    public $results = [];
    public $count;

    public $minQueryLength = 2;

    public $fetchLimit = 10;
    public $fetchCursor = 0;

    public function mount(Request $request)
    {
        $this->getSearchFilter($request);
    }

    public function render()
    {
        return view('livewire.home');
    }

    public function search()
    {

        $this->query = rtrim($this->query);

        if(strlen($this->query) >= $this->minQueryLength)
        {
            switch($this->filter)
            {
                case 'player_hash':
                    $this->results = CurseLog::select('player_hash')
                                        ->where('player_hash', 'like', $this->query.'%')
                                        ->groupBy('player_hash')
                                        ->orderBy('timestamp', 'desc')
                                        ->skip($this->fetchCursor)
                                        ->take($this->fetchLimit)
                                        ->get();

                    $this->count = Curselog::where('player_hash', 'like', $this->query.'%')->groupBy('player_hash')->count();
                    break;
                case 'character_name':
                    $this->results = LifeNameLog::with('character')
                                        ->where('name', 'like', $this->query.'%')
                                        ->orderBy('character_id', 'desc')
                                        ->skip($this->fetchCursor)
                                        ->take($this->fetchLimit)
                                        ->get();

                    $this->count = LifeNameLog::where('name', 'like', $this->query.'%')->count();

                    break;
                case 'curse_name':
                    $this->results = Yumlog::select('curse_name', 'player_hash', 'timestamp')
                                        ->where('curse_name', 'like', $this->query.'%')
                                        ->where('verified', 1)
                                        ->groupBy('player_hash')
                                        ->orderBy('character_id', 'desc')
                                        ->skip($this->fetchCursor)
                                        ->take($this->fetchLimit)
                                        ->get();

                    $this->count = Yumlog::where('curse_name', 'like', $this->query.'%')->where('verified', 1)->count();
                    break;
                case 'leaderboard':
                    $this->results = Leaderboard::where('leaderboard_name', 'like', $this->query.'%')
                                        ->orderBy('leaderboard_name', 'asc')
                                        ->skip($this->fetchCursor)
                                        ->take($this->fetchLimit)
                                        ->get();

                    $this->count = Leaderboard::where('leaderboard_name', 'like', $this->query.'%')->count();
                    break;
            }
        }else{
            $this->fetchCursor = 0;
            $this->results = [];
        }
    }

    public function setSearchFilter(Request $request, $filter)
    {
        $this->resetResults();
        $this->filter = $filter;
        $request->session()->put('filter', $filter);
    }

    public function getSearchFilter(Request $request)
    {
        $this->filter = $request->session()->get('filter', 'character_name');
    }

    public function increaseCursor()
    {
        $this->fetchCursor += 10;
        $this->search();
    }

    public function decreaseCursor()
    {
        $this->fetchCursor -= 10;
        $this->search();
    }

    public function resetResults()
    {
        $this->query = null;
        $this->results = [];
    }

}