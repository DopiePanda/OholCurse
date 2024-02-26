<?php

namespace App\Livewire\Contacts;

use Livewire\Component;
use Auth;
use Log;

use App\Models\CurseLog;

class InteractionList extends Component
{
    public $hash;
    public $selected;
    public $skip;
    public $take;
    public $order;

    public $interactions;
    public $result_count;

    protected $listeners = [
        'contactSaved' => '$refresh',
        'contactDeleted' => '$refresh',
        'forceRefresh' => '$refresh',
        'userPlayerHashVerified' => 'playerHashVerified',
    ];

    public function mount()
    {
            $this->hash = Auth::user()->player_hash;
            $this->selected = 'curse';
            $this->skip = 0;
            $this->take = 10;
            $this->order = 'desc';
    }
    
    public function render()
    {
        $this->result_count = CurseLog::where('reciever_hash', $this->hash)
                                ->where('type', $this->selected)
                                ->distinct('player_hash')
                                ->count();

        $this->interactions = CurseLog::with('leaderboard_recieved', 'name', 'contact')
                            ->where('reciever_hash', $this->hash)
                            ->where('type', $this->selected)
                            ->select('character_id', 'player_hash', 'timestamp')
                            ->skip($this->skip)
                            ->take($this->take)
                            ->groupBy('player_hash')
                            ->orderBy('created_at', $this->order)
                            ->get();

        return view('livewire.contacts.interaction-list');
    }

    public function getInteractions($type)
    {
        $this->selected = $type;
        $this->skip = 0;
    }

    public function updateLimit()
    {
        
    }

    public function updateOrder()
    {

    }

    public function nextPage()
    {
        if($this->result_count > ($this->skip + $this->take) && $this->result_count > $this->take)
        {
            $this->skip += $this->take;
        }
    }

    public function previousPage()
    {
        if($this->skip >= 0)
        {
            $this->skip -= $this->take;
        }else
        {
            $this->skip = 0;
        }
    }

    public function playerHashVerified()
    {
        Log::debug('Event triggered in interactions list');
        $this->hash = Auth::user()->player_hash;
        
        $this->selected = 'curse';
        $this->skip = 0;
        $this->take = 10;

        $this->getInteractions($this->selected);
        return redirect(request()->header('Referer'));
    }
}
