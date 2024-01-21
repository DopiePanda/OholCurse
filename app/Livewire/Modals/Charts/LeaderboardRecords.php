<?php

namespace App\Livewire\Modals\Charts;

use Livewire\Component;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\RadarChartModel;
use Asantibanez\LivewireCharts\Models\TreeMapChartModel;

class LeaderboardRecords extends Component
{
    public function render()
    {

        $lineChartModel = 
            (new LineChartModel())
                ->setTitle('Expenses by Type')
                ->addColumn('Food', 100, '#f6ad55')
                ->addColumn('Shopping', 200, '#fc8181')
                ->addColumn('Travel', 300, '#90cdf4')
            ;

        return view('livewire.modals.charts.leaderboard-records');
    }
}
