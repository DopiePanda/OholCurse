<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Log;
use DB;

use App\Models\CurseLog;
use App\Models\LifeLog;
use App\Models\LifeNameLog;
use App\Models\Leaderboard;
use App\Models\LeaderboardRecord;
use App\Models\UserContact;
use App\Models\PlayerScore;
use App\Models\ProfileRestriction;
use App\Models\Yumlog;
use App\Models\User;

class PlayerReportController extends Controller
{

    public function fetch(Request $request, $hash)
    {
        $time_start = microtime(true);

        if($this->isProfileRestricted($hash))
        {
            if(!Auth::user() || Auth::user()->id != 1)
            {
                return redirect()->route('search');
            }
        }

        // All curses/forgives/trusts sent
        $sent = CurseLog::with('leaderboard', 'scores', 'contact')
                        ->select('type', 'timestamp', 'character_id', 'reciever_hash')
                        ->where('player_hash', $hash)
                        ->where('type', '!=' ,'score')
                        ->where('hidden', 0)
                        ->orderBy('timestamp', 'desc')
                        ->get();

        $sent = $this->categorizeSent($sent, $hash);

        // All curses/forgives/trusts recieved
        $recieved = CurseLog::with('leaderboard_recieved', 'scores_recieved', 'contact_recieved')
                            ->select('type', 'timestamp', 'character_id', 'player_hash')
                            ->where('reciever_hash', $hash)
                            ->where('type', '!=' ,'score')
                            ->where('hidden', 0)
                            ->orderBy('timestamp', 'desc')
                            ->get();

        $recieved = $this->categorizeRecieved($recieved, $hash);

        $profile = $this->getPlayerProfile($hash);

        if($profile)
        {
            $scores = $this->getPlayerScores($profile, $hash);
        }

        if(Auth::user())
        {
            $contact = $this->getUserContact($hash);
        }

        return view('player.curses', [
            'hash' => $hash, 
            'profile' => $profile,
            'scores' => $scores ?? null,
            'contact' => $contact ?? null,
            'sent' => $sent, 
            'recieved' => $recieved, 
            'time' => $time_start,
        ]);
    }

    public function isProfileRestricted($hash)
    {
        $profile = ProfileRestriction::where('player_hash', $hash)
                    ->where('enabled', 1)
                    ->first();
        
        if($profile)
        {
            return true;
        }

        return false;
    }

    public function getUserContact($hash)
    {
        try{
            // Total curse score
            $contact = UserContact::where('hash', $hash)
                                ->where('user_id', Auth::user()->id)
                                ->select('type', 'nickname', 'phex_hash')
                                ->orderBy('id', 'desc')
                                ->first();

            if($contact)
            {
                return $contact;
            }
                                
            } catch(\Exception $e) {
                Log::error("Error while fetching contact for: $hash");
                Log::error($e);
            }

            return null;
    }

    public function getPlayerProfile($hash)
    {
        try
        {
            // Total curse score
            $profile = Leaderboard::where('player_hash', $hash)
                                ->select('leaderboard_name', 'leaderboard_id', 'player_hash')
                                ->orderBy('id', 'desc')
                                ->first();

            if($profile)
            {
                return $profile;
            }

            $profile = collect(['player_hash' => $hash]);

            return $profile;
                                
        } catch(\Exception $e) {
            Log::error("Error while fetching profile for: $hash in PlayerReportController");
        }
    }

    public function getPlayerScores($profile, $hash)
    {
        try{

            // Total curse score
            return PlayerScore::where('leaderboard_id', $profile->leaderboard_id)
                                ->select('curse_score', 'gene_score')
                                ->first();
    
            } catch(\Exception $e) {
                Log::error("Error while fetching scores for: $hash in PlayerReportController");
            }
    }

    

    public function lives(Request $request, $hash)
    {
        $time_start = microtime(true);

        $lives = LifeLog::with('name')
                    ->where('player_hash', $hash)
                    ->where('age', '>', 3)
                    ->where('type', 'death')
                    ->orderBy('character_id', 'desc')
                    ->get();

        $lives_normal = LifeLog::where('player_hash', $hash)
                    ->where('age', '>', 3)
                    ->where('type', 'death')
                    ->where('pos_x', '<', '-1')
                    ->where('pos_x', '>', '-100000000')
                    ->count();

        $lives_dt = LifeLog::where('player_hash', $hash)
                    ->where('age', '>', 3)
                    ->where('type', 'death')
                    ->where('pos_x', '<', '-100000000')
                    ->count();

        $name = Leaderboard::where('player_hash', $hash)
                            ->select('leaderboard_name', 'leaderboard_id')
                            ->orderBy('id', 'desc')
                            ->first();

        $donator = User::where('donator', 1)
                ->where('player_hash', $hash)
                ->first() ?? null;

        return view('player.lives', [
            'hash' => $hash, 
            'lives' => $lives,
            'lives_normal' => $lives_normal,
            'lives_dt' => $lives_dt,
            'name' => $name,
            'donator' => $donator,
            'time' => $time_start,
        ]);
    }

    public function reports(Request $request, $hash)
    {
        if(Auth::user())
        {
            if(Auth::user()->can('view all reports'))
            {
                $status = [0, 1, 2, 3, 4, 5];
            }else
            {
                $status = [1];
            }
        }else
        {
            $status = [1];
        }

        $time_start = microtime(true);

        $name = Leaderboard::where('player_hash', $hash)
                    ->select('leaderboard_name', 'leaderboard_id')
                    ->orderBy('id', 'desc')
                    ->first();

        $curse_count = CurseLog::where('reciever_hash', $hash)
                    ->where('type', 'curse')
                    ->count();

        $reports = Yumlog::select(DB::raw("(COUNT(character_id)) as count"), 'character_name', 'character_id', 'curse_name', 'gender', 'age', 'died_to', 'timestamp', 'status')
                    ->where('player_hash', $hash)
                    ->where('verified', 1)
                    ->where('visible', 1)
                    ->whereIn('status', $status)
                    ->has('curses', '>', '0')
                    ->groupBy('character_id')
                    ->orderBy('character_id', 'desc')
                    ->limit($curse_count)
                    ->get();

        $donator = User::where('donator', 1)
                ->where('player_hash', $hash)
                ->first() ?? null;

        return view('player.reports', [
            'hash' => $hash, 
            'name' => $name, 
            'reports' => $reports,
            'donator' => $donator,
            'time' => $time_start
        ]);
    }
    
    public function report(Request $request, $hash)
    {
        $result['curses_sent'] = CurseLog::where('player_hash', $hash)->where('type', 'curse')->orderBy('timestamp', 'desc')->get();
        $result['curses_recieved'] = CurseLog::where('reciever_hash', $hash)->where('type', 'curse')->orderBy('timestamp', 'desc')->get();

        $result['curse_score'] = CurseLog::where('player_hash', $hash)->where('type', 'score')->orderBy('id', 'desc')->first();

        $result['trusts_sent'] = CurseLog::where('player_hash', $hash)->where('type', 'trust')->orderBy('timestamp', 'desc')->get();
        $result['trusts_recieved'] = CurseLog::where('reciever_hash', $hash)->where('type', 'trust')->orderBy('timestamp', 'desc')->get();

        $result['forgives_sent'] = CurseLog::where('player_hash', $hash)->where('type', 'forgive')->orderBy('timestamp', 'desc')->get();
        $result['forgives_recieved'] = CurseLog::where('reciever_hash', $hash)->where('type', 'forgive')->orderBy('timestamp', 'desc')->get();
        
        $result['forgiven_all'] = CurseLog::where('player_hash', $hash)->where('type', 'all')->orderBy('timestamp', 'desc')->get();

        return view('player-report2', ['results' => $result, 'hash' => $hash]);
    }

    public function records(Request $request, $hash)
    {
        $time_start = microtime(true);

        $player = Leaderboard::where('player_hash', $hash)
                            ->select('leaderboard_name', 'leaderboard_id')
                            ->orderBy('id', 'desc')
                            ->first();
        if($player)
        {
            $records = LeaderboardRecord::with('lifeName:character_id,name', 'leaderboard:id,enabled,object_id,label,image', 'currentRecord:game_leaderboard_id,leaderboard_id,character_id,amount,timestamp')
                            ->whereHas('leaderboard', function($query) { return $query->where('enabled', '=', 1); })
                            ->select('game_leaderboard_id', 'object_id', 'leaderboard_id', 'character_id', 'timestamp', 'amount', 'created_at')
                            ->where('leaderboard_id', $player->leaderboard_id)
                            ->where('ghost', 0)
                            ->orderBy('amount', 'desc')
                            ->groupBy('game_leaderboard_id')
                            ->get();

            $ghostRecords = LeaderboardRecord::with('lifeName:character_id,name', 'leaderboard:id,enabled,object_id,label,image', 'currentGhostRecord:game_leaderboard_id,leaderboard_id,character_id,amount,timestamp')
                            ->whereHas('leaderboard', function($query) { return $query->where('enabled', '=', 1); })
                            ->select('game_leaderboard_id', 'object_id', 'leaderboard_id', 'character_id', 'timestamp', 'amount', 'created_at')
                            ->where('leaderboard_id', $player->leaderboard_id)
                            ->where('ghost', 1)
                            ->orderBy('amount', 'desc')
                            ->groupBy('game_leaderboard_id')
                            ->get();


        }

        $donator = User::where('donator', 1)
                ->where('player_hash', $hash)
                ->first() ?? null;

        return view('player.records', [
            'hash' => $hash, 
            'records' => $records ?? [],
            'ghostRecords' => $ghostRecords ?? [],
            'player' => $player,
            'donator' => $donator,
            'time' => $time_start,
        ]);
        
    }

    private function categorizeSent($object, $hash)
    {
        $data = [
            'curses' => [],
            'trusts' => [],
            'forgives' => [],
            'all' => [],
        ];

        foreach ($object as $line) 
        {
            $timezone = auth()->user()->timezone ?? 'UTC';
            $timedate = Carbon::createFromTimestamp($line->timestamp, $timezone);

            switch ($line->type) 
            {
                case 'curse':
                    array_push($data['curses'], [
                        'type' => $line->type, 
                        'timestamp' => $timedate ?? $line->timestamp,
                        'character_id' => $line->character_id ?? null,
                        'character_name' => $line->name->name ?? null,
                        'player_hash' => $hash ?? $line->player_hash,
                        'reciever_hash' => $line->reciever_hash ?? null,
                        'leaderboard' => $line->leaderboard->leaderboard_name ?? null,
                        'contact_name' => $line->contact ?? null,
                        'curse_score' => $line->scores->curse_score ?? null,
                        'gene_score' => $line->scores->gene_score ?? null,
                    ]);
                    break;
                case 'trust':
                    array_push($data['trusts'], [
                        'type' => $line->type, 
                        'timestamp' => $timedate ?? $line->timestamp,
                        'character_id' => $line->character_id ?? null,
                        'character_name' => $line->name->name ?? null,
                        'player_hash' => $hash ?? $line->player_hash,
                        'reciever_hash' => $line->reciever_hash ?? null,
                        'leaderboard' => $line->leaderboard->leaderboard_name ?? null,
                        'contact_name' => $line->contact ?? null,
                        'curse_score' => $line->scores->curse_score ?? null,
                        'gene_score' => $line->scores->gene_score ?? null,
                    ]);
                    break;
                case 'forgive':
                    array_push($data['forgives'], [
                        'type' => $line->type, 
                        'timestamp' => $timedate ?? $line->timestamp,
                        'character_id' => $line->character_id ?? null,
                        'character_name' => $line->name->name ?? null,
                        'player_hash' => $hash ?? $line->player_hash,
                        'reciever_hash' => $line->reciever_hash ?? null,
                        'leaderboard' => $line->leaderboard->leaderboard_name ?? null,
                        'contact_name' => $line->contact ?? null,
                        'curse_score' => $line->scores->curse_score ?? null,
                        'gene_score' => $line->scores->gene_score ?? null,
                    ]);
                    break;
                case 'all':
                    array_push($data['all'], [
                        'type' => $line->type, 
                        'timestamp' => $timedate ?? $line->timestamp,
                        'character_id' => $line->character_id ?? null,
                        'character_name' => null,
                        'player_hash' => $hash ?? $line->player_hash,
                        'reciever_hash' => $line->reciever_hash ?? null,
                        'leaderboard' => $line->scores->leaderboard_name ?? null,
                    ]);
                    break;         
            }
        }

        return $data;
    }

    private function categorizeRecieved($object, $hash)
    {
        $data = [
            'curses' => [],
            'trusts' => [],
            'forgives' => [],
            'all' => [],
        ];

        foreach ($object as $line) 
        {
            $timezone = auth()->user()->timezone ?? 'UTC';
            $timedate = Carbon::createFromTimestamp($line->timestamp, $timezone);

            switch ($line->type) 
            {
                case 'curse':
                    array_push($data['curses'], [
                        'type' => $line->type, 
                        'timestamp' => $timedate ?? $line->timestamp,
                        'character_id' => $line->character_id ?? null,
                        'character_name' => $line->name->name ?? null,
                        'player_hash' => $line->player_hash ?? null,
                        'reciever_hash' => $hash ?? $line->reciever_hash,
                        'leaderboard' => $line->leaderboard_recieved->leaderboard_name ?? null,
                        'contact_name' => $line->contact_recieved ?? null,
                        'curse_score' => $line->scores_recieved->curse_score ?? null,
                        'gene_score' => $line->scores_recieved->gene_score ?? null,
                    ]);
                    break;
                case 'trust':
                    array_push($data['trusts'], [
                        'type' => $line->type, 
                        'timestamp' => $timedate ?? $line->timestamp,
                        'character_id' => $line->character_id ?? null,
                        'character_name' => $line->name->name ?? null,
                        'player_hash' => $line->player_hash ?? null,
                        'reciever_hash' => $hash ?? $line->reciever_hash,
                        'leaderboard' => $line->leaderboard_recieved->leaderboard_name ?? null,
                        'contact_name' => $line->contact_recieved ?? null,
                        'curse_score' => $line->scores_recieved->curse_score ?? null,
                        'gene_score' => $line->scores_recieved->gene_score ?? null,
                    ]);
                    break;
                case 'forgive':
                    array_push($data['forgives'], [
                        'type' => $line->type, 
                        'timestamp' => $timedate ?? $line->timestamp,
                        'character_id' => $line->character_id ?? null,
                        'character_name' => $line->name->name ?? null,
                        'player_hash' => $line->player_hash ?? null,
                        'reciever_hash' => $hash ?? $line->reciever_hash,
                        'leaderboard' => $line->leaderboard_recieved->leaderboard_name ?? null,
                        'contact_name' => $line->contact_recieved ?? null,
                        'curse_score' => $line->scores_recieved->curse_score ?? null,
                        'gene_score' => $line->scores_recieved->gene_score ?? null,
                    ]);
                    break;
                case 'all':
                    array_push($data['all'], [
                        'type' => $line->type, 
                        'timestamp' => $timedate ?? $line->timestamp,
                        'character_id' => $line->character_id ?? null,
                        'character_name' => $line->name->name ?? null,
                        'player_hash' => $line->player_hash ?? null,
                        'reciever_hash' => $hash ?? $line->reciever_hash,
                        'leaderboard' => $line->leaderboard_recieved->leaderboard_name ?? null,
                    ]);
                    break;         
            }
        }

        return $data;
    }

}
