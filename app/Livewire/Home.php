<?php

namespace App\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;
use DB;
use Auth;

use App\Models\Report;
use App\Models\CurseLog;
use App\Models\Leaderboard;
use App\Models\LifeLog;
use App\Models\LifeNameLog;
use App\Models\Yumlog;

class Home extends Component
{

    protected $listeners = [
        'pageChanged' => '$refresh',
    ];

    public $query;

    public $filter;
    public $results;
    public $count;

    public $minQueryLength;

    public $fetchLimit;
    public $fetchCursor;

    public $status;

    public function mount(Request $request)
    {
        $this->results = [];
        $this->count = 0;
    
        $this->minQueryLength = 2;
    
        $this->fetchLimit = 10;
        $this->fetchCursor = 0;

        $this->getSearchFilter($request);
        
        if(Auth::user())
        {
            if(Auth::user()->can('view all reports'))
            {
                $this->status = [0, 1, 2, 3, 4, 5];
            }else
            {
                $this->status = [0, 1];
            }
        }else
        {
            $this->status = [0, 1];
        }
    }

    public function render()
    {
        return view('livewire.home');
    }

    public function search()
    {
        if(strlen($this->query) >= $this->minQueryLength)
        {
            $this->results = [];
            //$this->count = 0;
            
            if($this->filter == 'character_name')
            {
                $this->getCharacters();
            }
            elseif($this->filter == 'curse_name')
            {
                $this->getCurseNames();
            }
            elseif($this->filter == 'leaderboard')
            {
                $this->getLeaderboards();
            }
            else
            {
                $this->getPlayerHashes();
            }

        }else{
            $this->fetchCursor = 0;
            $this->results = [];
            $this->count = 0;
        }

        //$this->dispatch('pageChanged');
    }

    public function getCharacters()
    {
        $this->results = LifeNameLog::with('character')
                                    ->where('name', 'like', rtrim($this->query).'%')
                                    ->orderBy('character_id', 'desc')
                                    ->skip($this->fetchCursor)
                                    ->take($this->fetchLimit)
                                    ->get()
                                    ->toArray();

        $this->count = LifeNameLog::where('name', 'like', rtrim($this->query).'%')->count();
    }

    public function getCurseNames()
    {
        $this->results = Yumlog::select('id', 'curse_name', 'player_hash', 'timestamp', 'character_id')
                                ->where('curse_name', 'like', strtoupper($this->query).'%')
                                ->where('verified', 1)
                                ->whereIn('status', $this->status)
                                ->has('curses', '>', '0')
                                ->groupBy('player_hash')
                                ->orderBy('character_id', 'desc')
                                ->skip($this->fetchCursor)
                                ->take($this->fetchLimit)
                                ->get()
                                ->toArray();

        $this->count = Yumlog::where('curse_name', 'like', strtoupper($this->query).'%')->where('verified', 1)->whereIn('status', $this->status)->has('curses', '>', '0')->distinct('player_hash')->count();
    }

    public function getLeaderboards()
    {
        $this->results = Leaderboard::where('leaderboard_name', 'like', rtrim($this->query).'%')
                                    ->orderBy('leaderboard_name', 'asc')
                                    ->skip($this->fetchCursor)
                                    ->take($this->fetchLimit)
                                    ->get()
                                    ->toArray();

        $this->count = Leaderboard::where('leaderboard_name', 'like', rtrim($this->query).'%')->count();
    }

    public function getPlayerHashes()
    {
        $this->results = CurseLog::select('id', 'player_hash', 'timestamp')
                                ->where('player_hash', 'like', rtrim($this->query).'%')
                                ->groupBy('player_hash')
                                ->orderBy('timestamp', 'desc')
                                ->skip($this->fetchCursor)
                                ->take($this->fetchLimit)
                                ->get()
                                ->toArray();

        $this->count = CurseLog::where('player_hash', 'like', rtrim($this->query).'%')->distinct('player_hash')->count();
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
        if($this->fetchCursor < $this->count - $this->fetchLimit)
        {
            $this->fetchCursor += 10;
        }
        else
        {
            $this->fetchCursor = $this->count - $this->fetchLimit;
        }

        $this->search();
    }

    public function decreaseCursor()
    {
        if($this->fetchCursor >= $this->fetchLimit)
        {
            $this->fetchCursor -= 10;
        }
        else
        {
            $this->fetchCursor = 0;
        }
        
        $this->search();
        //dd($this->fetchLimit);
    }

    public function resetResults()
    {
        $this->query = null;
        $this->results = [];
    }

}