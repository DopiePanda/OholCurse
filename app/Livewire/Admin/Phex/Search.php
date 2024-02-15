<?php

namespace App\Livewire\Admin\Phex;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\UserContact;

class Search extends Component
{
    use WithPagination;

    public $contacts;

    public function mount()
    {
        $this->contacts = UserContact::with('phex:px_hash,px_name,olgc_hash,olgc_name', 'player:player_hash,leaderboard_name,leaderboard_id', 'user:id,username')
                            ->select('hash', 'phex_hash', 'nickname', 'user_id', 'type')
                            ->where('type', 'enemy')
                            ->get();

    }

    public function render()
    {
        return view('livewire.admin.phex.search', ['contacts' => $this->contacts]);
    }
}
