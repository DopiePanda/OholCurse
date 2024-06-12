<?php

namespace App\Livewire\Leaderboards;

use LivewireUI\Modal\ModalComponent;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use DB;

use App\Models\LeaderboardRecord;
use App\Models\GameLeaderboard;

class ChartModal extends ModalComponent
{
    public $leaderboard;
    public $records;

    public $previous_amount;

    public $filter_ghosts;

    public $chart_data;

    public function mount($leaderboard_id)
    {
        $this->leaderboard = GameLeaderboard::find($leaderboard_id);
        $this->filter_ghosts = false;

        $this->records = LeaderboardRecord::with('leaderboard', 'lifeName', 'playerName', 'player', 'character')
                    ->select('game_leaderboard_id', 'amount', 'character_id', 'leaderboard_id', 'timestamp')
                    ->where('game_leaderboard_id', $leaderboard_id)
                    ->where('ghost', $this->filter_ghosts)
                    ->orderBy('amount', 'desc')
                    ->get();

        $this->chart_data = LeaderboardRecord::select('amount', DB::raw("date_format(from_unixtime(timestamp),'%b %d, %Y %l:%i %p') as timestamp"))
            ->where('game_leaderboard_id', $this->leaderboard->id)
            ->where('ghost', $this->filter_ghosts)
            ->groupBy('timestamp')
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.leaderboards.chart-modal');
    }
}
