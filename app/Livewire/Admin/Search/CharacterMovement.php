<?php

namespace App\Livewire\Admin\Search;

use Livewire\Component;

use App\Models\LifeLog;
use App\Models\MapLog;

class CharacterMovement extends Component
{
    public $character;
    public $coordinates;

    public function mount()
    {
        if(auth()->user()->can('use admin tools') != true)
        {
            return redirect(route('search'));
        }
    }

    public function render()
    {
        return view('livewire.admin.search.character-movement');
    }

    public function search()
    {
        $life = LifeLog::where('character_id', $this->character)->where('type', 'birth')->first();

        if($life)
        {
            $map_movements = MapLog::with('object:id,name')
                            ->where('character_id', $life->character_id)
                            ->select('pos_x', 'pos_y', 'object_id', 'timestamp')
                            ->orderBy('timestamp', 'asc')
                            ->get();

            $this->coordinates = $map_movements;
        }
    }
}
