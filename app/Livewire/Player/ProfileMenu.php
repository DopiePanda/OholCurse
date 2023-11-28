<?php

namespace App\Livewire\Player;

use Livewire\Component;
use Auth;

use App\Models\Leaderboard;
use App\Models\CurseLog;
use App\Models\LifeLog;
use App\Models\Yumlog;
use App\Models\LeaderboardRecord;

class ProfileMenu extends Component
{

    public $hash;
    public $counts;

    public function mount($hash)
    {
        $this->hash = $hash;
        $this->getCounts();
    }

    public function render()
    {
        return view('livewire.player.profile-menu');
    }

    public function getCounts()
    {
        $player = Leaderboard::where('player_hash', $this->hash)
                            ->select('leaderboard_name', 'leaderboard_id')
                            ->first();

        $curses = CurseLog::where('type', '!=', 'score')
                            ->where('player_hash', $this->hash)
                            ->orWhere('reciever_hash', $this->hash)
                            ->count();

        $lives = LifeLog::where('player_hash', $this->hash)
                        ->where('age', '>', 3)
                        ->where('type', 'death')
                        ->count();
        if($player)
        {
            $records = LeaderboardRecord::where('leaderboard_id', $player->leaderboard_id)
                            ->whereHas('leaderboard', function($query) { return $query->where('enabled', '=', 1); })
                            ->select('object_id')
                            ->groupBy('object_id')
                            ->get();
        }else{
            $records = [];
        }
        


        if(Auth::user())
        {
            $role = Auth::user()->role;

            if($role == 'admin')
            {
                $status = [0, 1, 2, 3, 4];
            }else
            {
                $status = [1];
            }
        }else
        {
            $status = [1];
        }
        
        $reports = Yumlog::where('player_hash', $this->hash)
                            ->where('verified', 1)
                            ->whereIn('status', $status)
                            ->count();
        
        $counts = ['curses' => $curses, 'lives' => $lives, 'reports' => $reports, 'records' => $records];

        $this->counts = $counts;
    }
}
