<?php

namespace App\Http\Livewire\Admin\Leaderboards;

use Livewire\Component;

use App\Models\GameLeaderboard;

class Index extends Component
{

    public $leaderboards;

    protected $listeners = [
        'leaderboardCreated' => 'getAllLeaderboards',
        'leaderboardUpdated' => 'getAllLeaderboards'
    ];

    public function mount()
    {
        $this->getAllLeaderboards();
    }

    public function render()
    {
        return view('livewire.admin.leaderboards.index');
    }

    public function getAllLeaderboards()
    {
        $this->leaderboards = GameLeaderboard::with('object')->orderBy('id', 'desc')->get();
    }

    public function toggleLeaderboard($id)
    {
        $leaderboard = GameLeaderboard::find($id);

        if($leaderboard->enabled == 1)
        {
            $leaderboard->enabled = 0;
        }else
        {
            $leaderboard->enabled = 1;
        }
        
        $leaderboard->save();
        $this->getAllLeaderboards();
    }
}
