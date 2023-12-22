<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

use Auth;
use Log;

use App\Models\Yumlog;
use App\Models\CurseLog;
use App\Models\LifeLog;
use App\Models\LifeNameLog;

class ManualReportVerification extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:manual-report-verification {user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array
     */
    protected function promptForMissingArgumentsUsing()
    {
        return [
            'user_id' => 'Please enter the user ID you wish to verify reports for',
        ];
    }

    private $curseOffset = 3;
    private $forgiveOffset = 180;
    private $maxVerifyAttempts = 5;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user_id = $this->argument('user_id');
        $this->verifyUserCurses($user_id);
    }

    public function verifyUserCurses($user_id)
    {
        $yumlogs = Yumlog::where('user_id', $user_id)
                    ->where('verification_tries', '<', $this->maxVerifyAttempts)
                    ->get();

        $bar = $this->output->createProgressBar(count($yumlogs));
        $bar->start();

        foreach($yumlogs as $report)
        {
            
            $life = LifeLog::where('character_id', $report->character_id)
                        ->where('type', 'death')
                        ->first();

            if($life === null)
            {
                if ($report->verification_tries < $this->maxVerifyAttempts) 
                {
                    $report->verification_tries++;
                    $report->save();

                    $attempts_left = $this->maxVerifyAttempts - $report->verification_tries;
                }

                if($report->verification_tries >= $this->maxVerifyAttempts)
                {
                    $report->status = 2;
                    $report->save();
                }
                
            }else
            {

                $curseVerified = $this->verifyCurseLogEntry($life->player_hash, $report->timestamp);
                $forgiveVerified = $this->verifyForgiveLogEntry($life->player_hash, $report->timestamp);
                $lifeNameVerified = $this->verifyLifeNameLogEntry($report->character_id, $report->character_name);

                if($curseVerified == true || $forgiveVerified == true)
                {
                    if($lifeNameVerified == true)
                    {
                        $this->setCurseVerified($report, $life, $forgiveVerified);
                        $this->isCurseCheck($report, $life);
                    }
                }
            }

            $bar->advance();
        }

        $bar->finish();
    }

    public function setCurseVerified($report, $life, $forgive = false)
    {
  
        $report->player_hash = $life->player_hash;
        $report->gender = $life->gender;
        $report->age = $life->age;
        $report->died_to = $life->died_to;
        $report->pos_x = $life->pos_x ?? null;
        $report->pos_y = $life->pos_y ?? null;
        $report->verified = 1;
        $report->status = $forgive ? 5 : 1;
        $report->visible = 1;
        $report->save();

    }

    public function isCurseCheck($report, $life)
    {
        $curse = CurseLog::where('timestamp', '>=', ($report->timestamp - $this->curseOffset))
                        ->where('timestamp', '<=', ($report->timestamp + $this->curseOffset))
                        ->where('reciever_hash', $life->player_hash)
                        ->where('type', 'curse')
                        ->first();
        if($curse)
        {
            $curseCheck = CurseLog::where('timestamp', '<=', ($curse->timestamp + $this->forgiveOffset))
                            ->where('timestamp', '>=', $curse->timestamp)
                            ->where('reciever_hash', $life->player_hash)
                            ->where('player_hash', $curse->player_hash)
                            ->where('type', 'forgive')
                            ->count();

            $forgivenLater = CurseLog::where('timestamp', '>=', $curse->timestamp)
                            ->where('reciever_hash', $life->player_hash)
                            ->where('player_hash', $curse->player_hash)
                            ->where('type', 'forgive')
                            ->count();

            if($curseCheck > 0)
            {
                $report->status = 3;
                $report->visible = 1;
                $report->save();
            }

            if($forgivenLater > 0)
            {
                $report->status = 4;
                $report->visible = 1;
                $report->save();
            }

        }
    }

    public function verifyCurseLogEntry($hash, $timestamp)
    {
        try 
        {
            $count = CurseLog::where('timestamp', '>=', ($timestamp - $this->curseOffset))
                        ->where('timestamp', '<=', ($timestamp + $this->curseOffset))
                        ->where('reciever_hash', $hash)
                        ->where('type', 'curse')
                        ->count();

            if($count > 0)
            {
                return true;
            }

        } catch (\Throwable $th) 
        {
            Log::error('Could not find curse with hash: '.$hash);
        }

        return false;
    }

    public function verifyForgiveLogEntry($hash, $timestamp)
    {
        try 
        {
            $count = CurseLog::where('timestamp', '>=', ($timestamp - $this->curseOffset))
                        ->where('timestamp', '<=', ($timestamp + $this->curseOffset))
                        ->where('reciever_hash', $hash)
                        ->where('type', 'forgive')
                        ->count();

            if($count > 0)
            {
                return true;
            }

        } catch (\Throwable $th) 
        {
            Log::error('Could not find forgive with hash: '.$hash);
        }

        return false;
    }

    public function verifyLifeNameLogEntry($id, $name)
    {
        try 
        {
            $count = LifeNameLog::where('character_id', $id)
                            ->where('name', $name)
                            ->count();

            if($count > 0)
            {
                return true;
            }

        } catch (\Throwable $th) 
        {
            Log::error('Could not find life name with id/name: '.$id.'/'.$name);
        }
        

        return false;
    }
}
