<?php

namespace App\Livewire\Leaderboards;

use Livewire\Component;
use DB;

use App\Models\LeaderboardRecord;

class AllTime extends Component
{

    public $results;

    public $filter_ghosts;
    public $order_by_col;
    public $order_by_dir;

    public function mount()
    {
        $this->filter_ghosts = false;
        $this->order_by_col = "game_leaderboard_id";
        $this->order_by_dir = "desc";
        //$this->getAllTimeRecords();
    }

    public function render()
    {
        $this->results = LeaderboardRecord::with('leaderboard', 'lifeName', 'playerName', 'player', 'character')
                    ->select('game_leaderboard_id', DB::raw("(MAX(amount)) as amount"), 'character_id', 'leaderboard_id', 'timestamp')
                    ->where('game_leaderboard_id', '!=', null)
                    ->where('ghost', $this->filter_ghosts)
                    ->orderBy($this->order_by_col, $this->order_by_dir)
                    ->groupBy('game_leaderboard_id')
                    ->get();

        return view('livewire.leaderboards.all-time');
    }

    public function getAllTimeRecords()
    {
        $this->results = LeaderboardRecord::with('leaderboard', 'lifeName', 'playerName', 'player', 'character')
                    ->select('game_leaderboard_id', DB::raw("(MAX(amount)) as amount"), 'character_id', 'leaderboard_id', 'timestamp')
                    ->where('game_leaderboard_id', '!=', null)
                    ->where('ghost', $this->filter_ghosts)
                    ->orderBy($this->order_by_col, $this->order_by_dir)
                    ->groupBy('game_leaderboard_id')
                    ->get();
    }

    public function toggleGhostRecords()
    {
        $this->filter_ghosts = !$this->filter_ghosts;
    

        //$this->getAllTimeRecords();
    }

    public function setOrderByColumn()
    {
        switch ($this->order_by_col) {
            case 'game_leaderboard_id':
                $this->order_by_col = "game_leaderboard_id";
                break;

            case 'timestamp':
                $this->order_by_col = "timestamp";
                break;

            case 'amount':
                $this->order_by_col = "amount";
                break;
            
            default:
                $this->order_by_col = "game_leaderboard_id";
                break;
        }
    }

    public function setOrderByDirection()
    {
        
    }
}
