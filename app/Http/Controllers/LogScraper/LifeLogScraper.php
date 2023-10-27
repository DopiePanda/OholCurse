<?php

namespace App\Http\Controllers\LogScraper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Log;

use App\Models\LifeLog;

class LifeLogScraper extends Controller
{
    private $url = "http://publicdata.onehouronelife.com/publicLifeLogData/lifeLog_bigserver2.onehouronelife.com/";
    private $storage_url = "logs/lifes/";

    private $file_name_format = "Y_mF_d_l";
    private $file_type = ".txt";

    private $date_period;

    public function __construct()
    {
        $start = Carbon::now();
        $end = Carbon::now()->subDays(4);

        $this->date_period = new \DatePeriod(
            new \DateTime($end->format('Y-m-d')),
            new \DateInterval('P1D'),
            new \DateTime($start->format('Y-m-d')),
       );
    }

    public function scrapeLog()
    {
        $time_start = microtime(true);
        Log::info('LIFE LOG scraper started');

        foreach($this->date_period as $key => $date)
        {
            $file_name = $this->getLogFileName($date->format($this->file_name_format));
            
            $this->downloadLog($file_name);
            $this->processLog($file_name);
            $this->deleteLog($file_name);
        }

        $time_end = microtime(true);
        $time = round(($time_end - $time_start), 3);
        Log::info('LIFE LOG scraper finished in: '.$time.' seconds');
    }

    private function downloadLog($file_name)
    {
        if(Storage::exists($this->storage_url.$file_name))
        {
            Log::info("LIFE LOG already exist: ".$file_name);
        }else
        {
            $file = file_get_contents($this->getLogFileUrl($file_name));
            Storage::disk('local')->put($this->storage_url.$file_name, $file);
        }
    }

    public function processLog($file_name)
    {
        if(Storage::exists($this->storage_url.$file_name))
        {
            File::lines(Storage::path($this->storage_url.$file_name))->each(function ($line) {
                
                
                $line = str_replace('(', '', $line);
                $line = str_replace(')', '', $line);
                $line = str_replace(' => ', ' ', $line);

                $line = explode(' ', $line);
                $type = $line[0];

                switch($type)
                {
                    case 'B':
                        $this->processBirthLine($line);
                        break;

                    case 'D':
                        $this->processDeathLine($line);
                        break;

                    default:
                }

            });
        }
    }

    private function deleteLog($file_name)
    {
        if(Storage::exists($this->storage_url.$file_name))
        {
            Storage::delete($this->storage_url.$file_name);
        }
    }

    private function processBirthLine($line)
    {
        $line[6] = str_replace('parent=', '', $line[6]);
        $line[7] = str_replace('pop=', '', $line[7]);
        $line[8] = str_replace('chain=', '', $line[8]);

        if(isset($line[9]))
        {
            $line[9] = str_replace('race=', '', $line[9]);
        }else{
            $line[9] = 'unknown';
        }
        

        if($line[4] == 'F')
        {
            $line[4] = 'female';
        }else
        {
            $line[4] = 'male';
        }

        if($line[9] == 'A')
        {
            $line[9] = 'desert';

        }elseif($line[9] == 'C')
        {
            $line[9] = 'jungle';

        }elseif($line[9] == 'F')
        {
            $line[9] = 'arctic';

        }else{
            $line[9] = 'language';
        }

        LifeLog::updateOrCreate(
            [
                'character_id' => $line[2],
                'type' => 'birth',
            ],
            [
                'timestamp' => $line[1],
                'player_hash' => $line[3],
                'gender' => $line[4],
                'location' => $line[5],
                'parent_id' => $line[6],
                'population' => $line[7],
                'yum_chain' => $line[8],
                'family_type' => $line[9],
            ]
        );
    }

    private function processDeathLine($line)
    {
        $line[4] = str_replace('age=', '', $line[4]);
        $line[8] = str_replace('pop=', '', $line[8]);

        if($line[5] == 'F')
        {
            $line[5] = 'female';
        }else
        {
            $line[5] = 'male';
        }

        LifeLog::updateOrCreate(
            [
                'character_id' => $line[2],
                'type' => 'death',
            ],
            [
                'timestamp' => $line[1],
                'player_hash' => $line[3],
                'age' => $line[4],
                'gender' => $line[5],
                'location' => $line[6],
                'died_to' => $line[7],
                'population' => $line[8],
            ]
        );
    }

    private function getLogFileName($date)
    {
        return $date.$this->file_type;
    }

    private function getFileStorageUrl($date)
    {
        return $this->storage_url.$date.$this->file_type;
    }

    private function getLogFileUrl($file_name)
    {
        return $this->url.$file_name;
    }

    private function printLink($link)
    {
        return '<a href="'.$link.'">Logfile</a>';
    }
}
