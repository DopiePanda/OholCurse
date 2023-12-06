<?php

namespace App\Http\Controllers\Objects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Log;

use App\Models\YumLog;


class CurseNameLogImporter extends Controller
{
    private $file = 'ohol/curse_names_yum_log.txt';

    public $curse_lines = [];
    public $curse_names = [];
    public $curses = [];


    public function parseYumLogFile()
    {
        $start_time = microtime(true);

        if(Storage::exists($this->file))
        {
            
            File::lines(Storage::path($this->file))->each(function ($line) 
            {
                if(Str::contains($line, '| forgive |'))
                {
                    try 
                    {
                        $this->processLine($line);
                    } catch (\Throwable $th) 
                    {
                        Log::error($th);
                    }
                    
                }
            });

            //$this->deleteLog();
        }
    }

    public function processLine($line)
    {
        /*
            $parts[0] = timestamp | 1695342437 
            $parts[1] = type | forgive 
            $parts[2] = character_id and name | 6665043 SEASON BEAR
            $parts[3] = curse_name | TOWER VEST
        */

        $parts = explode(' | ', $line);

        // Seperate character_id and character_name
        $timestamp = $parts[0];
        $character = explode(' ', $parts[2]);
        $character_id = $character[0];

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

        try
        {
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
        } catch(\Throwable $th)
        {   
            Log::error('Error while importing YumLog with curse names:');
            Log::error($th);
        }
    }

    public function deleteLog()
    {
        if(Storage::exists($this->file))
        {
            Storage::delete($this->file);
            //Log::info('Log deleted');
        }
    }
}

