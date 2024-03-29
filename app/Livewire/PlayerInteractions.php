<?php

namespace App\Livewire;

use Livewire\Component;

use Carbon\Carbon;
use Auth;
use Log;
use DB;

use App\Models\Leaderboard;
use App\Models\User;

class PlayerInteractions extends Component
{

    public $hash;
    public $profile;
    public $donator;

    public $start_time;

    public function mount($hash)
    {
        if($hash == '')
        {
            abort(404);
        }

        $this->hash = $hash;


        $this->start_time = microtime(true);

        $this->profile = Leaderboard::with('score')
                    ->where('player_hash', $this->hash)
                    ->select('leaderboard_name', 'leaderboard_id', 'player_hash')
                    ->orderBy('id', 'desc')
                    ->first();

        $this->donator = User::where('donator', 1)->where('player_hash', $this->hash)->first() ?? null;
    }

    public function render()
    {
        return view('livewire.player-interactions');
    }
}
