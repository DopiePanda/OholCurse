<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Auth;
use Log;

use App\Models\Yumlog;
use App\Models\LifeLog;
use App\Models\User;


class ProcessYumLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-yum-log {user} {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    public $user;
    public $path;

    public $curse_lines = [];
    public $curse_names = [];
    public $curses = [];

    public $last_birth = [];
    public $last_player = [];
    public $last_players = [];

    public $my_ids = [];

    public function handle()
    {
        $this->user = User::find($this->argument('user'));
        $this->path = $this->argument('path');

        $this->processLog();
    }

    public function processLog()
    {
        if(Storage::exists($this->path.'/yumlog.txt'))
        {
            
            File::lines(Storage::path($this->path.'/yumlog.txt'))->each(function ($line) 
            {
                if(Str::contains($line, '| my_birth |'))
                {
                    $this->last_birth = explode(' | ', $line);
                }

                if(Str::contains($line, '| my_id |'))
                {
                    if(count($this->my_ids) < 5)
                    {
                        array_push($this->my_ids, explode(' | ', $line));
                    }
                }

                if(Str::contains($line, '| player |'))
                {
                    //$this->last_player = explode(' | ', $line);
                    array_push($this->last_players, explode(' | ', $line));
                }

                if(Str::contains($line, '| curse |'))
                {
                    try {
                        $this->processLine($line);
                    } catch (\Throwable $th) {
                        Log::error($th);
                    }
                    
                }
            });

            $this->verifyUserHash();
        }

        $this->deleteLog();
    }

    public function processLine($line)
    {
        /*
            $parts[0] = timestamp | 1379020415 
            $parts[1] = type | curse 
            $parts[2] = character_id and name | 6641482 MELISA PLIMMER
            $parts[3] = curse_name | HIDE FORM
        */

        $parts = explode(' | ', $line);

        // Seperate character_id and character_name
        $character = explode(' ', $parts[2]);
        $character_id = $character[0];

        foreach ($this->last_players as $player) 
        {
            if (($player[0] - $parts[0]) <= 3) {
                if($character_id == $player[2])
                {
                    $this->last_player = $player;
                }
            }
        }

        //dd($this->last_player);

        if($parts[0] >= ($this->last_birth[0]-3) && $parts[0] <= ($this->last_birth[0]+3))
        {
            return false;
        }elseif($parts[0] >= ($this->last_player[0]-3) && $parts[0] <= ($this->last_player[0]+3) && $this->last_player[2] == $character_id)
        {
            return false;
        }else
        {
            // Set up a timestamp that is behind by 8 years from today
            $offset = \Carbon\Carbon::now()->subYears(8)->timestamp;
            $timestamp = $parts[0];

            /* Check if the offset is greater than the timestamp
                If so, add 10 years. Good job Jason. */
            if($offset > $timestamp)
            {
                $timestamp = ($timestamp+315532801);
            }

            // Check if character has only firstname or both first and last
            if(count($character) == 2)
            {
                $name = $character[1];
            }elseif(count($character) == 3)
            {
                $name = $character[1].' '.$character[2];
            }else
            {
                $name = null;
            }

            Yumlog::updateOrCreate(
                [
                    'user_id' => Auth::user()->id,
                    'timestamp' => $timestamp,
                    'character_id' => $character_id,
                ], 
                [
                    'character_name' => $name,
                    'curse_name' => $parts[3],
                ]
            );
        }
    }

    public function verifyUserHash()
    {
        if(count($this->my_ids) == 5)
        {
            $hashes = array();
            $matches = 0;

            foreach ($this->my_ids as $id) 
            {
                $life = LifeLog::where('character_id', $id[2])->first();
                if($life != null)
                {
                    array_push($hashes, $life->player_hash);
                }
            }

            $user = $this->user;

            if(count(array_unique($hashes)) === 1 && $user->player_hash == null)
            {   
                $user->player_hash = $hashes[0];
                $user->save();
                Log::debug('User Player Hash has been set');
            }
        }
    }

    public function deleteLog()
    {
        if(Storage::exists($this->path.'/yumlog.txt'))
        {
            Storage::delete($this->path.'/yumlog.txt');
            //Log::info('Log deleted');
        }
    }
}
