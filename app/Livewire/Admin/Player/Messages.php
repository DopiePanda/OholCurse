<?php

namespace App\Livewire\Admin\Player;

use LivewireUI\Modal\ModalComponent;

use App\Models\LifeLog;
use App\Models\Leaderboard;
use App\Models\PlayerMessage;

class Messages extends ModalComponent
{
    public $hash;
    public $leaderboard;
    public $character_ids;
    
    public $query;
    public $matches = [];

    public function mount($hash)
    {
        if(auth()->user()->can('view player messages') != true)
        {
            return redirect(route('search'));
        }

        $this->leaderboard = Leaderboard::where('player_hash', $hash)->first();

        $this->character_ids = LifeLog::select('character_id')
            ->where('type', 'birth')
            ->where('player_hash', $this->leaderboard->player_hash)
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.player.messages');
    }

    public function search()
    {
        $this->matches = PlayerMessage::whereIn('life_id', $this->character_ids)
            ->where('message', 'like', '%'.$this->query.'%')
            ->get();
    }
}
