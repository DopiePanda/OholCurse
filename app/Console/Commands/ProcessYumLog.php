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
use App\Models\PhexHash;


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
                if(Str::contains($line, '| phex_list |'))
                {
                    try 
                    {
                        $this->processPhexHash($line);
                    } 
                    catch (\Throwable $th) 
                    {
                        Log::channel('yumlog')->error($th);
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

        /*
            $player[0] = timestamp | 1708891075
            $player[0] = type | player
            $player[0] = character id | 7126301
            $player[0] = age | age:3
        */
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

        // If curse timestamp is greater than or equal the timestamp of the last birth -3 seconds
        // AND curse timestamp is less than or equal to the timestamp of last birth + 3 seconds
        // If this is true, it suggests the curse was sent in a previous life
        if($parts[0] >= ($this->last_birth[0]-3) && $parts[0] <= ($this->last_birth[0]+3))
        {
            return false;

        // If curse timestamp is greater than or equal the timestamp of the last player line -3 seconds
        // AND curse timestamp is less than or equal to the timestamp of the last player line + 3 seconds
        // If this is true, it suggests the curse was sent in a previous life
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

    public function processPhexHash($line)
    {
        /*
            $parts[0] = timestamp | 1379020415 
            $parts[1] = type | phex_list
            $parts[2] = phex hash | 627474f34827254d1b373b3cb3b63691d8f7e445
            $parts[3] = phex name | DopiePanda
            $parts[4] = character ID | 7001138
            $parts[5] = character name | UGNE WUNDER

            1705115066 | phex_list | 627474f34827254d1b373b3cb3b63691d8f7e445 | DopiePanda | 7001138 | UGNE WUNDER
        */

        $parts = explode(' | ', $line);

        if (count($parts) == 6) 
        {
            $life = LifeLog::select('character_id', 'player_hash')->where('character_id', $parts[4])->where('type', 'death')->first();
        }

        try 
        {
            PhexHash::updateOrCreate(
                [
                    'olgc_name' => $parts[3],
                    'olgc_hash' => Str::take($parts[2], 8),
                ], 
                [
                    'olgc_hash_full' => $parts[2],
                    'player_hash' => $life ? $life->player_hash : null,
                    'character_id' => $life ? $life->character_id : null,
                ]
            );
        } 
        catch (\Throwable $th) 
        {
            Log::channel('yumlog')->error($th);
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
