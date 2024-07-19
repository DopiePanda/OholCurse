<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Carbon\Carbon;
use DB;

use App\Models\LifeLog;

class DeathScanner extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.death-scanner';

    public $deaths;
    public $days;

    public function mount()
    {
        $this->days = 7;
        $this->getDeaths();
    }

    public function getDeaths()
    {
        $ts = Carbon::now()->subDays($this->days);


        $this->deaths = DB::table('life_logs')
            ->selectRaw('leaderboards.leaderboard_name, life_logs.player_hash, life_logs.died_to, count(life_logs.character_id) as count')
            ->join('leaderboards', 'leaderboards.player_hash', '=', 'life_logs.player_hash')
            ->where('type', 'death')
            ->where('died_to', 'like', 'killer_%')
            ->where('timestamp', '>=', $ts->timestamp)
            ->orderBy('count', 'DESC')
            ->groupBy('life_logs.player_hash')
            ->get();
    }
}
