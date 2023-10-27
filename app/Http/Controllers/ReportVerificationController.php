<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Log;

use App\Models\Yumlog;
use App\Models\CurseLog;
use App\Models\LifeLog;
use App\Models\LifeNameLog;

class ReportVerificationController extends Controller
{
    public function verifyAllByUser($id)
    {
        $yumlogs = Yumlog::where('user_id', $id)
                    ->where('verified', 0)
                    ->get();

        foreach($yumlogs as $curse)
        {
            $lifeLog = LifeLog::where('character_id', $curse->character_id)
                        ->where('type', 'death')
                        ->first();

            $timestampMin = $curse->timestamp-60;
            $timestampMax = $curse->timestamp+60;

            if($lifeLog)
            {
                try 
                {
                    $countCurseLog = CurseLog::where('timestamp', '>=', $timestampMin)
                        ->where('timestamp', '<=', $timestampMax)
                        ->where('reciever_hash', $lifeLog->player_hash)
                        ->where('type', 'curse')
                        ->count();

                    $countLifeName = LifeNameLog::where('character_id', $curse->character_id)
                        ->where('name', $curse->character_name)
                        ->count();

                } catch (\Throwable $th) 
                {
                    Log::error('Could not find hash for character: '.$curse->character_id);
                }
    

                if($countCurseLog > 0 && $countLifeName > 0)
                {
                    $pos = explode(',', $lifeLog->location);

                    $curse->player_hash = $lifeLog->player_hash;
                    $curse->gender = $lifeLog->gender;
                    $curse->age = $lifeLog->age;
                    $curse->died_to = $lifeLog->died_to;
                    $curse->pos_x = $pos[0];
                    $curse->pos_y = $pos[1];
                    $curse->verified = 1;
                    $curse->save();
                }
            }
        }

        return 'Reports attempted verified.';
    }
}
