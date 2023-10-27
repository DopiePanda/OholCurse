<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CurseLog;
use App\Models\Leaderboard;

class CurseLogController extends Controller
{
    public function syncCurseScores()
    {
        $scores = CurseLog::where('type', 'score')->orderBy('timestamp', 'asc')->get();
        
        foreach($scores as $score)
        {
            $curses = CurseLog::where('reciever_hash', $score->player_hash)->where('curse_score', null)->get();

            foreach($curses as $curse)
            {
                $curse->curse_score = $score->curse_score;
                $curse->save();
            }
        }
    }

    public function getScores()
    {
        $scores = CurseLog::where('type', 'score')
                    ->where('curse_score', '>', 0)
                    ->select('player_hash', 'curse_score')
                    ->groupBy('player_hash')
                    ->get();
        dd(count($scores));
        
        $missing = array();
        
        foreach($scores as $score)
        {
            $leaderboard = Leaderboard::where('player_hash', $score->player_hash)->first();

            if($leaderboard)
            {
                $leaderboard->curse_score = $score['curse_score'];
                $leaderboard->save();
            }else
            {
                array_push($missing, $score->player_hash);
            }
        }

        dd($missing);
    }
}
