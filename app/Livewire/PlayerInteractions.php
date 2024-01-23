<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;

use Carbon\Carbon;
use Auth;
use Log;
use DB;

use App\Models\CurseLog;
use App\Models\Leaderboard;

class PlayerInteractions extends Component
{

    #[Url]
    public $hash = '';

    public $profile;

    public $sent;
    public $recieved;

    public $curses_sent, $curses_recieved;
    public $forgives_sent, $forgives_recieved;
    public $trusts_sent, $trusts_recieved;

    public $start_time;

    public function mount()
    {
        if($this->hash == '')
        {
            abort(404);
        }

        $this->start_time = microtime(true);

        $this->profile = Leaderboard::with('score')
                    ->where('player_hash', $this->hash)
                    ->select('leaderboard_name', 'leaderboard_id', 'player_hash')
                    ->orderBy('id', 'desc')
                    ->first();

        $this->sent = CurseLog::with('leaderboard', 'scores', 'contact')
                    ->select('type', 'timestamp', 'character_id', 'reciever_hash')
                    ->where('player_hash', $this->hash)
                    ->where('type', '!=' ,'score')
                    ->where('hidden', 0)
                    ->orderBy('timestamp', 'desc')
                    ->get();

        $this->recieved = CurseLog::with('leaderboard_recieved', 'scores_recieved', 'contact_recieved')
                    ->select('type', 'timestamp', 'character_id', 'player_hash')
                    ->where('reciever_hash', $this->hash)
                    ->where('type', '!=' ,'score')
                    ->where('hidden', 0)
                    ->orderBy('timestamp', 'desc')
                    ->get();

        $this->curses_sent = collect();
        $this->forgives_sent = collect();
        $this->trusts_sent = collect();

        $this->curses_recieved = collect();
        $this->forgives_recieved = collect();
        $this->trusts_recieved = collect();

        $this->sortInteractions();
        //dd($this->curses_recieved);
    }

    public function render()
    {
        return view('livewire.player-interactions');
    }

    public function sortInteractions()
    {
        foreach($this->sent as $sent)
        {
            if($sent->type == 'curse')
            {
                $this->curses_sent[] = $sent;
            }
            elseif($sent->type == 'forgive')
            {
                $this->forgives_sent[] = $sent;
            }
            elseif($sent->type == 'trust')
            {
                $this->trusts_sent[] = $sent;
            }
            else
            {

            }
        }

        foreach($this->recieved as $recieved)
        {
            if($recieved->type == 'curse')
            {
                $this->curses_recieved[] = $recieved;
            }
            elseif($recieved->type == 'forgive')
            {
                $this->forgives_recieved[] = $recieved;
            }
            elseif($recieved->type == 'trust')
            {
                $this->trusts_recieved[] = $recieved;
            }
            else
            {

            }
        }
    }
}
