<?php

namespace App\Livewire\Player;

use Livewire\Component;
use Auth;

use App\Models\UserContact;
use App\Models\Leaderboard;
use App\Models\ProfileBadge;

class ProfileHeader extends Component
{
    public $profile;
    public $contact;
    public $badges;

    public $hash;
    public $donator;

    protected $listeners = [
        'contactSaved' => 'refresh',
        'contactDeleted' => '$refresh'
    ];

    public function mount($hash, $donator)
    {
        $this->hash = $hash;
        $this->donator = $donator;
        
        $this->profile = Leaderboard::where('player_hash', $this->hash)->first();

        if(Auth::user())
        {
            $this->contact = UserContact::where('user_id', Auth::user()->id)->where('hash', $this->hash)->first();
        }

        $this->badges = ProfileBadge::with('badge')->where('player_hash', $hash)->orderBy('achieved_at', 'asc')->get();
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
