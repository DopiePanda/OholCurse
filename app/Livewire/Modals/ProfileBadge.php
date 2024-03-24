<?php

namespace App\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;

use App\Models\ProfileBadge as Badges;

class ProfileBadge extends ModalComponent
{
    public $hash;
    public $badges;
    public $badge;


    public function mount($hash, $badge_id = null)
    {
        $this->hash = $hash;

        $this->badges = Badges::with('badge')->where('player_hash', $hash)->get();

        if($badge_id != null)
        {
            $this->badge = Badges::with('badge')->where('id', $badge_id)->first();
        }
    }

    public function render()
    {
        return view('livewire.modals.profile-badge');
    }
}
