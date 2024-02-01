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

class InteractionRecieved extends Component
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
        $interactions = CurseLog::with('leaderboard_recieved:player_hash,leaderboard_name', 'scores_recieved:player_hash,gene_score,curse_score', 'contact_recieved:hash,nickname,type', 'name:character_id,name')
                    ->select('type', 'timestamp', 'character_id', 'player_hash')
                    ->where('reciever_hash', $this->hash)
                    ->where('type', $this->type)
                    ->where('hidden', 0)
                    ->groupBy(['player_hash'])
                    ->orderBy('timestamp', 'desc')
                    ->paginate(12, pageName: "$this->type-recieved-page");

        return view('livewire.player.component.interaction-recieved', ['interactions' => $interactions]);
    }

    public function setHeaderData()
    {
        switch ($this->type) 
        {
            case 'curse':
                $this->title = "Curses recieved";
                $this->handle = "2";
                break;

            case 'forgive':
                $this->title = "Forgives recieved";
                $this->handle = "4";
                break;

            case 'trust':
                $this->title = "Trusts recieved";
                $this->handle = "6";
                break;
            
            default:
                # code...
                break;
        }
    }
}
