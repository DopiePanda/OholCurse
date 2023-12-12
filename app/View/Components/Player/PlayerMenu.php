<?php

namespace App\View\Components\Player;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

use App\Models\Leaderboard;
use App\Models\LifeLog;
use App\Models\Yumlog;
use App\Models\LeaderboardRecord;

class PlayerMenu extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        public string $hash;
        public string $reportCount;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $player = Leaderboard::where('player_hash', $this->hash)
                            ->select('leaderboard_name', 'leaderboard_id')
                            ->first();

        $lives = LifeLog::where('player_hash', $this->hash)
                        ->where('age', '>', 3)
                        ->where('type', 'death')
                        ->count();

        $records = LeaderboardRecord::where('leaderboard_id', $player->leaderboard_id)
                            ->count();


        if(Auth::user())
        {
            $role = Auth::user()->id;

            if($role == 1)
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
        
        $counts = ['lives' => $lives, 'reports' => $reports, 'records' => $records];

        return view('components.player.menu', ['hash' => $this->hash, 'counts' => $counts]);
    }

    public function getCounts()
    {
        $player = Leaderboard::where('player_hash', $this->hash)
                            ->select('leaderboard_name', 'leaderboard_id')
                            ->first();

        $lives = LifeLog::where('player_hash', $this->hash)
                        ->where('age', '>', 3)
                        ->where('type', 'death')
                        ->count();

        $records = LeaderboardRecord::where('leaderboard_id', $player->leaderboard_id)
                            ->count();


        if(Auth::user())
        {
            $role = Auth::user()->id;

            if($role == 1)
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
        
        $counts = ['lives' => $lives, 'reports' => $reports, 'records' => $records];

        return $counts;
    }
}
