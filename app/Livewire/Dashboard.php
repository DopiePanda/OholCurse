<?php

namespace App\Livewire;

use Livewire\Component;
use Auth;
use Log;

use App\Models\Report;
use App\Models\Yumlog;
use App\Models\CurseLog;
use App\Models\LifeLog;
use App\Models\LifeNameLog;
use App\Models\UserContact;

class Dashboard extends Component
{
    protected $listeners = [
        'reportAdded' => '$refresh',
        'reportDeleted' => '$refresh',
        'logProcessed' => 'verifyCurses',
        'verificationComplete' => '$refresh',
        'logEntriesDeleted' => '$refresh',
        'logEntryDeleted' => '$refresh',
    ];

    public $reports = [];
    public $yumlogs;

    private $curseOffset = 3;
    private $forgiveOffset = 180;
    private $maxVerifyAttempts = 5;

    public $skip;
    public $take;
    public $limit;
    private $status = [];

    public function mount()
    {   
        $this->skip = 0;
        $this->take = 25;
        $this->limit = 25;
    }

    public function render()
    {
        $status = $this->getStatus();

        $this->yumlogs = Yumlog::where('user_id', Auth::user()->id)
                        ->where('verification_tries', '<', $this->maxVerifyAttempts)
                        ->whereIn('status', $status)
                        ->select('timestamp', 'character_id', 'character_name', 'curse_name', 'player_hash', 'verified', 'status', 'created_at')
                        ->groupBy('curse_name')
                        ->orderBy('timestamp', 'desc')
                        ->skip($this->skip)
                        ->take($this->take)
                        ->get();

        //dd($this->yumlogs);

        return view('livewire.dashboard');
    }

    public function getStatus()
    {
        if(Auth::user()->can('view all reports'))
        {
            $status = [0, 1, 2, 3, 4, 5];
        }else
        {
            $status = [0, 1, 3, 4];
        }

        return $status;
    }

    public function nextPage()
    {
        if($this->take >= 25)
        {
            $this->skip = $this->skip + $this->limit;
            $status = $this->getStatus();

            $this->yumlogs = Yumlog::where('user_id', Auth::user()->id)
                        ->where('verification_tries', '<', $this->maxVerifyAttempts)
                        ->whereIn('status', $status)
                        ->select('timestamp', 'character_id', 'character_name', 'curse_name', 'player_hash', 'verified', 'status', 'created_at')
                        ->groupBy('curse_name')
                        ->orderBy('timestamp', 'desc')
                        ->skip($this->skip)
                        ->take($this->take)
                        ->get();

            //dd($this->skip);
        }
    }

    public function previousPage()
    {
        if($this->take >= 25 && $this->skip >= 0)
        {
            $this->skip = $this->skip - $this->limit;
            $status = $this->getStatus();

            $this->yumlogs = Yumlog::where('user_id', Auth::user()->id)
                        ->where('verification_tries', '<', $this->maxVerifyAttempts)
                        ->whereIn('status', $status)
                        ->select('timestamp', 'character_id', 'character_name', 'curse_name', 'player_hash', 'verified', 'status', 'created_at')
                        ->groupBy('curse_name')
                        ->orderBy('timestamp', 'desc')
                        ->skip($this->skip)
                        ->take($this->take)
                        ->get();

            //dd($this->skip);
        }
    }

    public function verifyCurses()
    {
        $yumlogs = Yumlog::where('user_id', Auth::user()->id)
                    ->where('verified', 0)
                    ->where('verification_tries', '<', $this->maxVerifyAttempts)
                    ->where('status', '!=', 2)
                    ->where('status', '!=', 5)
                    ->get();

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
                    $this->alert('warning', "Could not verify report for: $report->character_id. Report will be archived after $attempts_left more attempts.");
                }
                if($report->verification_tries >= $this->maxVerifyAttempts)
                {
                    $report->status = 2;
                    $report->save();
                    $this->alert('error', "Report for: $report->character_id has been archived");
                }
                
            }else
            {

                $curseVerified = $this->verifyCurseLogEntry($life->player_hash, $report->timestamp);
                $forgiveVerified = $this->verifyForgiveLogEntry($life->player_hash, $report->timestamp);
                $lifeNameVerified = $this->verifyLifeNameLogEntry($report->character_id, $report->character_name);

                //$this->alert('success', 'Checkpoint two reached');

                if($curseVerified == true || $forgiveVerified == true)
                {
                    if($lifeNameVerified == true)
                    {
                        $this->setCurseVerified($report, $life, $forgiveVerified);
                        $this->isCurseCheck($report, $life);
                        $this->alert('success', 'Verified report for: '.$report->character_name);
                    }
                }
            }
        }

        $this->dispatch('verificationComplete');
        $this->alert('info', 'Attempt to verify curses was successful');
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
        $report->visible = 1;

        if($forgive == true)
        {
            $report->status = 5;
        }
        else
        {
            $report->status = 1;
        }

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
                //$this->alert('success', 'Found curse entry for : '.$hash);
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
                //$this->alert('success', 'Found curse entry for : '.$hash);
                return true;
            }

        } catch (\Throwable $th) 
        {
            Log::error('Could not find curse with hash: '.$hash);
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
                //$this->alert('success', 'Found life name entry for : '.$name);
                return true;
            }

        } catch (\Throwable $th) 
        {
            Log::error('Could not find life name with id/name: '.$id.'/'.$name);
        }
        

        return false;
    }

    public function deleteYumlogEntry($id)
    {
        try 
        {
            Yumlog::where('user_id', Auth::user()->id)
                    ->where('id', $id)
                    ->delete();
                    
            $this->alert('success', 'Log entry deleted');

        } catch (\Throwable $th) 
        {
            Log::error("Could not delete single Yumlog entry for ".Auth::user()->username);
            $this->alert('error', 'Error: Coild not delete log entry');
        }

        $this->dispatch('logEntryDeleted');
    }

    public function deleteAllYumlogEntries()
    {
        try 
        {
            Yumlog::where('user_id', Auth::user()->id)
                    ->delete();

            $this->alert('success', 'All log entries deleted.');

        } catch (\Throwable $th) 
        {
            Log::error("Could not delete all Yumlog entries for ".Auth::user()->username);
            $this->alert('error', 'Error: Coild not delete all log entries');
        }

        $this->dispatch('logEntriesDeleted');
    }
}
