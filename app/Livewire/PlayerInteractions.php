<?php

namespace App\Livewire;

use Livewire\Component;

use Carbon\Carbon;
use Auth;
use Log;
use DB;

use App\Models\Leaderboard;
use App\Models\User;
use App\Models\ProfileBadge;

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

        if(strlen($hash) < 40)
        {
            $leaderboard = Leaderboard::where('leaderboard_id', $hash)->first();
            if($leaderboard)
            {
                $this->hash = $leaderboard->player_hash;
            }
            else
            {
                return abort(404);
            }
            
        }
        else
        {
            $this->hash = $hash;
        }

        $this->start_time = microtime(true);

        $this->profile = Leaderboard::with('score')
                    ->where('player_hash', $this->hash)
                    ->select('leaderboard_name', 'leaderboard_id', 'player_hash')
                    ->orderBy('id', 'desc')
                    ->first();

        $this->donator = User::where('donator', 1)->where('player_hash', $this->hash)->first() ?? null;

        $this->aprilFoolsBadge();
    }

    public function render()
    {
        return view('livewire.player-interactions');
    }

    private function aprilFoolsBadge()
    {
        if(date('d.m') != '01.04')
        {
            return;
        }

        if(!Auth::user())
        {
            return;
        }

        if(Auth::user()->player_hash == null)
        {
            return;
        }

        ProfileBadge::updateOrCreate(
            [
                'player_hash' => Auth::user()->player_hash,
                'badge_id' => 2,
            ], 
            [
                'achieved_at' => time()
            ]
        );
    }
}
