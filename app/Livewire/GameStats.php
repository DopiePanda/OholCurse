<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;

use App\Models\LifeLog;

class GameStats extends Component
{

    public $days = 7;
    public $time;

    public $lives;
    public $accounts;
    public $minutes_played;

    public $males, $females;
    public $artic, $language, $jungle, $desert;

    public $eves, $eve_first, $eve_last, $eve_movement;

    public function mount()
    {
        $this->time = microtime(true);
        $ts = Carbon::now()->subDays($this->days);

        $lives = LifeLog::where('type', 'death')
            ->where('timestamp', '>=', $ts->timestamp)
            ->get();

        $this->lives = $lives;

        $this->minutes_played = $lives->pluck('age')->sum();
        $this->accounts = $lives->unique('player_hash')->count();

        $this->males = $lives->where('gender', 'male')->count();
        $this->females = $lives->where('gender', 'female')->count();

        $this->artic = $lives->where('family_tpe', 'arctic')->count();
        $this->language = $lives->where('family_tpe', 'language')->count();
        $this->jungle = $lives->where('family_tpe', 'jungle')->count();
        $this->desert = $lives->where('family_tpe', 'desert')->count();

        $this->eves = LifeLog::with('name')
            ->select('pos_x', 'character_id')
            ->where('type', 'birth')
            ->where('timestamp', '>=', $ts->timestamp)
            ->where('parent_id', '')
            ->where('pos_x', '>', -1000000)
            ->where('pos_x', '<', 50000)
            ->orderBy('id', 'asc')
            ->get();

        $this->eve_first = $this->eves->first();
        $this->eve_last = $this->eves->last();
        $this->eve_movement = $this->eve_first->pos_x - $this->eve_last->pos_x;
    }

    public function render()
    {
        return view('livewire.game-stats');
    }
}
