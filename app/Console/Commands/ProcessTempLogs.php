<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;
use Log;

use App\Models\Yumlog;
use App\Models\LifeLog;
use App\Models\CurseLog;
use App\Models\CurseLogTemp;

class ProcessTempLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-temp-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start_time = microtime(true);

        $success = 0;
        $fail = 0;
        $wrong = 0;

        $temp_logs = CurseLogTemp::all();

        foreach ($temp_logs as $log) 
        {
            if($log->character_id > 3500000)
            {
                // Check if life exists with submitted character ID
                $life = LifeLog::where('character_id', $log->character_id)
                ->where('type', 'death')
                ->first();

                if($life)
                {
                    $offset = 3;

                    // Check if curse log entry exists with submitted timestamp and life log player hash
                    $curse = CurseLog::where('timestamp', '>=', ($log->timestamp - $offset))
                            ->where('timestamp', '<=', ($log->timestamp + $offset))
                            ->where('reciever_hash', $life->player_hash)
                            ->where('type', 'forgive')
                            ->first();

                    if($curse)
                    {
                        if($log->hide == true)
                        {
                            // If forgive entry is found in curse logs, set entry to hidden
                            $curse->hidden = 1;
                            $curse->save();
                        }
                        else
                        {
                            // If forgive entry is found in curse logs, set entry to hidden
                            $curse->hidden = 0;
                            $curse->save();
                        }

                        // Since life log entry is found, update Yumlog report with life information and set as verified
                        try 
                        {
                            $report = Yumlog::where('user_id', $log->user_id)
                                        ->where('character_id', $log->character_id)
                                        ->first();
                                        
                            $this->setVerified($report, $life);
                            $log->delete();

                            $success++;
                        } 
                        catch (\Throwable $th) 
                        {
                            Log::channel('yumlog')->error($th);
                        } 
                    }
                    else
                    {
                        // If forgive entry is not found, log a notice on it
                        Log::channel('yumlog')->notice("Curse log entry not found for character: $log->character_id, ts: $log->timestamp, hash: $life->player_hash");
                        $log->attempts = $log->attempts+1;
                        $log->save();

                        $fail++;
                    }
                }
                else
                {
                    // If life log entry is not found, create a temporary entry on it for re-verification at next log import
                    Log::channel('yumlog')->notice("Life log entry not found for character: $log->character_id, ts: $log->timestamp");
                    $log->attempts = $log->attempts+1;
                    $log->save();

                    $fail++;
                }
            }
            else
            {
                $log->delete();
                $wrong++;
            }
        }

        $end_time = microtime(true);
        $time = round(($end_time - $start_time), 3);

        Log::channel('yumlog')->info("Processed ".count($temp_logs)." in $time seconds");
        Log::channel('yumlog')->info("Success: $success, failed: $fail, wrong server: $wrong");
    }

    public function setVerified($report, $life)
    {
        $report->player_hash = $life->player_hash;
        $report->gender = $life->gender;
        $report->age = $life->age;
        $report->died_to = $life->died_to;
        $report->pos_x = $life->pos_x ?? null;
        $report->pos_y = $life->pos_y ?? null;
        $report->verified = 1;
        $report->visible = 1;
        $report->save();
    }
}
