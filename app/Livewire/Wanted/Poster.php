<?php

namespace App\Livewire\Wanted;

use Livewire\Component;

use App\Models\FoodLog;

use DB;

class Poster extends Component
{

    public $records;

    public function mount()
    {
        $this->records = DB::table('food_logs')
            ->join('life_logs', 'food_logs.character_id', 'life_logs.character_id')
            ->join('leaderboards', 'life_logs.player_hash', 'leaderboards.player_hash')
            ->select('food_logs.timestamp', 'food_logs.character_id', 'life_logs.player_hash', 'leaderboards.leaderboard_name', DB::raw('COUNT(food_logs.character_id) as count'))
            ->where('object_id', 31)
            ->groupBy('character_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.wanted.poster');
    }
}