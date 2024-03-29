<?php

namespace App\Livewire\Player;

use Livewire\Component;
use Auth;
use DB;

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

        $curses = CurseLog::where('hidden', 0)
                        ->whereIn('type', ['curse', 'forgive', 'trust'])
                        ->where(function($query) {
                            $query->where('player_hash', $this->hash)
                                ->orWhere('reciever_hash', $this->hash);
                        })
                        ->distinct(['reciever_hash', 'player_hash'])
                        ->count();

        $lives = LifeLog::where('player_hash', $this->hash)
                        ->where('age', '>', 3)
                        ->where('type', 'death')
                        ->count();
        if($player)
        {
            $normal = LeaderboardRecord::where('leaderboard_id', $player->leaderboard_id)
                            ->where('ghost', 0)
                            ->whereHas('leaderboard', function($query) { return $query->where('enabled', '=', 1); })
                            ->count(DB::raw('DISTINCT game_leaderboard_id'));

            $ghost = LeaderboardRecord::where('leaderboard_id', $player->leaderboard_id)
                            ->where('ghost', 1)
                            ->whereHas('leaderboard', function($query) { return $query->where('enabled', '=', 1); })
                            ->count(DB::raw('DISTINCT game_leaderboard_id'));

            $records = $normal + $ghost;

        }else{
            $records = 0;
        }
        


        if(Auth::user())
        {
            $role = Auth::user()->id;

            if($role == 1)
            {
                $status = [0, 1, 2, 3, 4, 5];
            }else
            {
                $status = [1];
            }
        }else
        {
            $status = [1];
        }

        $curse_count = CurseLog::where('reciever_hash', $this->hash)
                    ->where('type', 'curse')
                    ->count();
        
        $reports = Yumlog::where('player_hash', $this->hash)
                            ->where('verified', 1)
                            ->whereIn('status', $status)
                            ->has('curses', '>', '0')
                            ->count();

        if($curse_count < $reports)
        {
            $reports = $curse_count;
        }

        $counts = ['curses' => $curses, 'lives' => $lives, 'reports' => $reports, 'recordsCount' => $records];

        $this->counts = $counts;
    }
}
