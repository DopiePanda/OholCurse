<?php

namespace App\Livewire\Admin\Player;

use LivewireUI\Modal\ModalComponent;
use App\Models\CurseLog;


class Profile extends ModalComponent
{
    public $playerHash;
    public $playerType;
    public $channel;

    public $curses;

    public function mount($hash, $type, $channel)
    {
        $this->playerHash = $hash;
        $this->playerType = $type;
        $this->channel = $channel;

        $this->getCurses();
    }

    public function render()
    {
        return view('livewire.admin.player.profile');
    }

    public function getCurses()
    {
        if($this->channel == 'recieved')
        {
            $this->curses = CurseLog::with('leaderboard', 'leaderboard_recieved')
                ->where('type', $this->playerType)
                ->where('reciever_hash', $this->playerHash)
                ->orderBy('id', 'desc')
                ->get();
        }
        if($this->channel == 'sent')
        {
            $this->curses = CurseLog::with('leaderboard', 'leaderboard_recieved')
                ->where('type', $this->playerType)
                ->where('player_hash', $this->playerHash)
                ->orderBy('id', 'desc')
                ->get();
        }
    }

    public function toggle($id)
    {
        $interaction = CurseLog::find($id);
        $interaction->hidden = !$interaction->hidden;
        $interaction->save();

        $this->getCurses();
    }
}
