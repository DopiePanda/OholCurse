<?php

namespace App\Livewire\Player;

use Livewire\Component;

use Carbon\Carbon;
use Auth;
use Log;
use DB;

use App\Models\Leaderboard;
use App\Models\LifeLog;
use App\Models\User;

class Lives extends Component
{
    public $start_time;

    public $hash;
    public $profile;
    public $donator;

    public $lives_normal;
    public $lives_dt;

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

        $this->lives_normal = LifeLog::where('player_hash', $this->hash)
                    ->where('age', '>', 3)
                    ->where('type', 'death')
                    ->where('pos_x', '<', '-1')
                    ->where('pos_x', '>', '-100000000')
                    ->count();

        $this->lives_dt = LifeLog::where('player_hash', $this->hash)
                    ->where('age', '>', 3)
                    ->where('type', 'death')
                    ->where('pos_x', '<', '-100000000')
                    ->count();
    }

    public function render()
    {
        return view('livewire.player.lives');
    }
}
