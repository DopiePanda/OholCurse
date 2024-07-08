<?php

namespace App\Livewire\Admin\Player;

use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use DB;

use App\Models\GrieferProfile;
use App\Models\LifeLog;

class BirthCheck extends Component
{
    public $profiles;
    public $hash;
    public $lives;

    public $children = [];

    public $filter_sid;
    public $filter_dt;

    public function mount()
    {
        if(auth()->user()->can('use admin tools') != true)
        {
            return redirect(route('search'));
        }
        
        $this->profiles = GrieferProfile::all()->pluck('player_hash');

        $this->killers();
    }

    public function render()
    {
        return view('livewire.admin.player.birth-check');
    }

    public function search()
    {
        $this->lives = LifeLog::select('character_id')
            ->where('player_hash', $this->hash)
            ->where('type', 'death')
            ->get()
            ->pluck('character_id');

        //dd($this->lives);

        $this->children = LifeLog::with('name', 'leaderboard', 'griefer', 'death')
            ->whereIn('player_hash', $this->profiles)
            ->whereIn('parent_id', $this->lives);

        if($this->filter_dt)
        {
            $this->children = $this->children->where('pos_x', '>', -100000000);
        }

        if($this->filter_sid)
        {
            $this->children = $this->children->whereHas('death', function (Builder $query) {
                $query->where('age', '>', '1');
            });
        }

        $this->children = $this->children->orderBy('timestamp', 'desc')
            ->get();
    }

    public function overview()
    {
        $lives = LifeLog::whereIn('player_hash', $this->profiles)
            ->where('type', 'birth')
            ->where('parent_id', '!=', 0)
            ->where('pos_x', '>', -100000000)
            ->whereHas('death', function (Builder $query) {
                $query->where('age', '>', '3');
            })
            ->get()
            ->pluck('parent_id');

        $parents = LifeLog::select('player_hash', 'character_id', DB::raw("(COUNT(player_hash)) as count"))
            ->whereIn('character_id', $lives)
            ->where('type', 'birth')
            ->groupBy('player_hash')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        dd($parents);
    }

    public function killers()
    {
        $lives = LifeLog::select('player_hash', 'character_id', 'died_to')
            ->where('died_to', 'like', 'killer%')
            ->where('type', 'death')
            ->where('pos_x', '>', -100000000)
            ->limit(10)
            ->get();

        $filtered = $lives->map(function ($item) {
            $killer = explode('_', $item->died_to);
            $item->died_to = $killer[1];
            return $item;
        });

        //dd($filtered->toArray());
    }
}
