<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use DB;

use App\Models\LifeLog;
use App\Models\CurseLog;

class GameStats extends Component
{

    public $days = 7;
    public $time;
    public $date;

    public $lives, $deaths, $eves, $trusted;

    public function mount()
    {
        $this->time = microtime(true);
        $this->getData();
    }

    public function render()
    {
        return view('livewire.game-stats');
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

        $this->trusted = Cache::remember('stats_trusted', $seconds, function () use ($ts) {
            return CurseLog::select(DB::raw("DISTINCT player_hash"), 'reciever_hash', DB::raw("COUNT(id) as count"))
            ->with('leaderboard')
            ->where('type', 'trust')
            ->groupBy('reciever_hash')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

            $this->trusted = $this->trusted->unqiue('player_hash');
            $this->trusted = $this->trusted->values()->all();        
        });
    }
}
