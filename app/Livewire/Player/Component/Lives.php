<?php

namespace App\Livewire\Player\Component;

use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Livewire\Component;

use Carbon\Carbon;
use Auth;
use Log;
use DB;

use App\Models\LifeLog;

class Lives extends Component
{
    use WithPagination, WithoutUrlPagination; 

    public $hash;
    public $take;
    public $order;

    public function mount($hash)
    {
        $this->hash = $hash;
        $this->take = 25;
        $this->order = 'desc';
    }

    public function render()
    {
        $lives = LifeLog::with('name')
                    ->where('player_hash', $this->hash)
                    ->where('age', '>', 3)
                    ->where('type', 'death')
                    ->orderBy('character_id', $this->order)
                    ->paginate($this->take, pageName: "life-page");

        return view('livewire.player.component.lives', ['lives' => $lives]);
    }

    public function updateLimit()
    {
        
    }

    public function updateOrder()
    {

    }
}
