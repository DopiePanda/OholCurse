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

    public $filter_ghosts;

    public $chart_name;
    public $labels;
    public $dataset;

    public function mount($leaderboard_id, $ghost)
    {
        $this->leaderboard = GameLeaderboard::find($leaderboard_id);
        $this->filter_ghosts = $ghost;
        $this->chart_name = $this->leaderboard->label;

        $this->setChartData();
    }

    public function render()
    {
        $this->records = LeaderboardRecord::with('leaderboard', 'lifeName', 'playerName', 'player', 'character')
                    ->select('game_leaderboard_id', 'amount', 'character_id', 'leaderboard_id', 'timestamp')
                    ->where('game_leaderboard_id', $this->leaderboard->id)
                    ->where('multi', $this->leaderboard->multi)
                    ->where('ghost', $this->filter_ghosts)
                    ->orderBy('timestamp', 'desc')
                    ->get();

        return view('livewire.leaderboards.chart-modal');
    }

    public function setChartData()
    {
        $data = LeaderboardRecord::select('amount', 'timestamp', DB::raw("date_format(from_unixtime(timestamp),'%b %d, %Y %l:%i %p') as date"))
            ->where('game_leaderboard_id', $this->leaderboard->id)
            ->where('multi', $this->leaderboard->multi)
            ->where('ghost', $this->filter_ghosts)
            ->orderBy('timestamp', 'asc')
            ->get();

        $this->dataset = $data
            ->pluck('amount')
            ->toArray();

        $this->labels = $data
            ->pluck('date')
            ->toArray();
    }

    public function toggleGhost()
    {
        $this->filter_ghosts = !$this->filter_ghosts;
        $this->setChartData();
        $this->dispatch('updateChart');
    }
}
