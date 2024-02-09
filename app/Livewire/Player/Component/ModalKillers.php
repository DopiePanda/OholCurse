<?php

namespace App\Livewire\Player\Component;

use LivewireUI\Modal\ModalComponent;

use App\Models\LifeLog;
use App\Models\Leaderboard;

class ModalKillers extends ModalComponent
{
    public $killers;
    public $killer_ids;

    public $players;
    public $player;

    public function mount(array $killers, $hash)
    {
        $this->player = Leaderboard::where('player_hash', $hash)->first(); 

        $this->killers = $killers; 
        $this->killer_ids = array_keys($this->killers);
        $this->players = LifeLog::with('name:character_id,name', 'death:timestamp,character_id,age,died_to', 'leaderboard:player_hash,leaderboard_name,leaderboard_id', 'leaderboard.score')
                            ->select('timestamp', 'player_hash', 'character_id', 'parent_id', 'gender', 'family_type')
                            ->where('type', 'birth')
                            ->whereIn('character_id', $this->killer_ids)
                            ->orderBy('timestamp', 'desc')
                            ->get();
    }

    public function render()
    {
        return view('livewire.player.component.modal-killers');
    }
}
