<?php

namespace App\Livewire\Admin\Player;

use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use DB;

use App\Models\GrieferProfile;
use App\Models\LifeLog;
use App\Models\Leaderboard;

class TwinCheck extends Component
{
    public $leaderboard;

    public $profiles;
    public $hash;
    public $lives;

    public $siblings = [];

    public $filter_sid;
    public $filter_dt;

    public function mount()
    {
        if(auth()->user()->can('use admin tools') != true)
        {
            return redirect(route('search'));
        }
        
        $this->profiles = GrieferProfile::all()->pluck('player_hash');
    }

    public function render()
    {
        return view('livewire.admin.player.twin-check');
    }

    public function search()
    {
        $this->leaderboard = Leaderboard::where('player_hash', $this->hash)->first();

        $this->lives = LifeLog::select('parent_id')
            ->where('player_hash', $this->hash)
            ->where('type', 'birth')
            ->get()
            ->pluck('parent_id');

        //dd($this->lives);

        $this->siblings = LifeLog::with('name', 'leaderboard', 'griefer', 'death')
            ->whereIn('parent_id', $this->lives)
            ->whereIn('player_hash', $this->profiles)
            ->where('parent_id', '!=', '0');

        if($this->filter_dt)
        {
            $this->siblings = $this->siblings->where('pos_x', '>', -100000000);
        }

        if($this->filter_sid)
        {
            $this->siblings = $this->siblings->whereHas('death', function (Builder $query) {
                $query->where('age', '>', '1');
            });
        }

        $this->siblings = $this->siblings->orderBy('timestamp', 'desc')
            ->get();

        //dd($this->siblings);
    }
}