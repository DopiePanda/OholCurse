<?php

namespace App\Livewire\Player\Component;

use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Livewire\Component;

use Carbon\Carbon;
use Auth;
use Log;
use DB;

use App\Models\CurseLog;
use App\Models\Leaderboard;

class InteractionSent extends Component
{
    use WithPagination, WithoutUrlPagination; 

    public $type;
    public $hash;
    
    public $title, $handle, $count;

    public function mount($type, $hash)
    {
        $this->type = $type;
        $this->hash = $hash;
        
        $this->setHeaderData();
    }

    public function render()
    {
        $interactions = CurseLog::with('leaderboard:player_hash,leaderboard_name', 'scores:player_hash,gene_score,curse_score', 'contact:hash,nickname,type', 'name:character_id,name')
                    ->select('type', 'timestamp', 'character_id', 'reciever_hash')
                    ->where('player_hash', $this->hash)
                    ->where('type', $this->type)
                    ->where('hidden', 0)
                    ->groupBy('reciever_hash')
                    ->orderBy('timestamp', 'desc')
                    ->paginate(12, pageName: "$this->type-page");
        //dd($interactions->total());
        return view('livewire.player.component.interaction-sent', ['interactions' => $interactions]);
    }

    public function setHeaderData()
    {
        switch ($this->type) 
        {
            case 'curse':
                $this->title = "Curses sent";
                $this->handle = "1";
                break;

            case 'forgive':
                $this->title = "Forgives sent";
                $this->handle = "3";
                break;

            case 'trust':
                $this->title = "Trusts sent";
                $this->handle = "5";
                break;
            
            default:
                # code...
                break;
        }
    }
}
