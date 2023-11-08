<?php

namespace App\Livewire\Player;

use Livewire\Component;
use Auth;

use App\Models\UserContact;
use App\Models\Leaderboard;

class ProfileHeader extends Component
{
    public $profile;
    public $contact;

    public $hash;

    protected $listeners = [
        'contactSaved' => 'refresh',
        'contactDeleted' => '$refresh'
    ];

    public function mount($hash)
    {
        $this->hash = $hash;
        $this->profile = Leaderboard::where('player_hash', $this->hash)->first();

        if(Auth::user())
        {
            $this->contact = UserContact::where('user_id', Auth::user()->id)->where('hash', $this->hash)->first();
        }
    }
    
    public function render()
    {
        return view('livewire.player.profile-header');
    }

    public function refresh()
    {
        $this->profile = Leaderboard::where('player_hash', $this->hash)->first();
        if(Auth::user())
        {
            $this->contact = UserContact::where('user_id', Auth::user()->id)->where('hash', $this->hash)->first();
        }
    }
    
}
