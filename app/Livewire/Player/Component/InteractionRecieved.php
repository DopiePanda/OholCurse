<?php

namespace App\Livewire\Player\Component;

use Livewire\Component;

class InteractionRecieved extends Component
{

    public $interactions;
    public $title;
    public $handle;
    public $count;

    public function mount($interactions, $title, $handle)
    {
        $this->interactions = $interactions;
        $this->title = $title;
        $this->handle = $handle;
        $this->count = count($interactions);
    }

    public function render()
    {
        return view('livewire.player.component.interaction-recieved');
    }
}
