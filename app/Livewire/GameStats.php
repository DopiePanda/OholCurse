<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use App\Models\LifeLog;

class GameStats extends Component
{

    public $days = 7;
    public $time;
    public $date;

    public $lives, $deaths;
    public $accounts;
    public $minutes_played;

    public $males, $females;
    public $artic, $language, $jungle, $desert;

    public $eves, $eve_first, $eve_last, $eve_movement;

    public function mount()
    {
        $this->time = microtime(true);
        $this->getData();
        //$this->initData();
    }

    public function render()
    {
        return view('livewire.game-stats');
    }

    public function setCache()
    {
        $ts = Carbon::now()->subDays($this->days);

        $lives = LifeLog::select('age', 'player_hash', 'gender', 'family_type', 'timestamp', 'created_at')
            ->where('type', 'death')
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

        $this->eves = LifeLog::select('pos_x', 'character_id')
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

    public function getData()
    {
        $ts = Carbon::now()->subDays($this->days);
        $seconds = 60 * 30;

        $this->lives = Cache::remember('stats_lives', $seconds, function () use ($ts) {
            return LifeLog::where('timestamp', '>=', $ts->timestamp)
                ->where('type', 'birth')
                ->selectRaw('SUM(age) AS minutes_played')
                ->selectRaw("COUNT(CASE WHEN gender = 'male' THEN 1 END) AS males")
                ->selectRaw("COUNT(CASE WHEN gender = 'female' THEN 1 END) AS females")
                ->selectRaw("COUNT(CASE WHEN family_type = 'arctic' THEN 1 END) AS fam_arctic")
                ->selectRaw("COUNT(CASE WHEN family_type = 'language' THEN 1 END) AS fam_language")
                ->selectRaw("COUNT(CASE WHEN family_type = 'jungle' THEN 1 END) AS fam_jungle")
                ->selectRaw("COUNT(CASE WHEN family_type = 'desert' THEN 1 END) AS fam_desert")
                ->first();
        });

        $this->deaths = Cache::remember('stats_deaths', $seconds, function () use ($ts) {
            return LifeLog::where('timestamp', '>=', $ts->timestamp)
                ->where('type', 'death')
                ->selectRaw('SUM(age) AS minutes_played')
                ->selectRaw("COUNT(DISTINCT player_hash) AS accounts")
                ->selectRaw("COUNT(id) AS lives_played")
                ->first();
        });

        $this->eves = Cache::remember('stats_eves', $seconds, function () use ($ts) {
            return LifeLog::where('type', 'birth')
                ->where('timestamp', '>=', $ts->timestamp)
                ->where('parent_id', '')
                ->where('pos_x', '>', -1000000)
                ->where('pos_x', '<', 50000)
                ->selectRaw("COUNT(id) AS eve_count")
                ->selectRaw("MIN(pos_x) AS eve_pos_last")
                ->selectRaw("MAX(pos_x) AS eve_pos_first")
                ->first();
        });
    }

    public function initData()
    {
        $this->minutes_played = $this->lives->pluck('age')->sum();
        $this->accounts = $this->lives->unique('player_hash')->count();

        $this->males = $this->lives->where('gender', 'male')->count();
        $this->females = $this->lives->where('gender', 'female')->count();

        $this->artic = $this->lives->where('family_tpe', 'arctic')->count();
        $this->language = $this->lives->where('family_tpe', 'language')->count();
        $this->jungle = $this->lives->where('family_tpe', 'jungle')->count();
        $this->desert = $this->lives->where('family_tpe', 'desert')->count();

        $this->eve_first = $this->eves->first();
        $this->eve_last = $this->eves->last();
        $this->eve_movement = $this->eve_first->pos_x - $this->eve_last->pos_x;
    }
}
