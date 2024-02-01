<?php

namespace App\Livewire;

use Livewire\Component;

use Carbon\Carbon;
use Auth;
use Log;
use DB;

use App\Models\Leaderboard;

class PlayerInteractions extends Component
{

    public $hash;
    public $profile;

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
    }

    public function render()
    {
        return view('livewire.player-interactions');
    }
}
