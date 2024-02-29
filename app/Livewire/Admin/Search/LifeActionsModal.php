<?php

namespace App\Livewire\Admin\Search;

use LivewireUI\Modal\ModalComponent;

use App\Models\LifeLog;
use App\Models\MapLog;

class LifeActionsModal extends ModalComponent
{

    public $character_id;
    public $coordinates;


    public function mount($character_id)
    {
        if(auth()->user()->can('use admin tools') != true)
        {
            return redirect(route('search'));
        }

        $this->character_id = $character_id;
        $this->search();
    }

    public function render()
    {
        return view('livewire.admin.search.life-actions-modal');
    }

    public function search()
    {
        $life = LifeLog::where('character_id', $this->character_id)->where('type', 'birth')->first();

        if($life)
        {
            $this->coordinates = MapLog::with('object:id,name')
                            ->where('character_id', $life->character_id)
                            ->select('pos_x', 'pos_y', 'object_id', 'timestamp')
                            ->orderBy('timestamp', 'asc')
                            ->get();
        }
    }

    public static function modalMaxWidth(): string
    {
        return '4xl';
    }
}
