<?php

namespace App\Http\Controllers\LogScraper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Log;

use App\Models\LifeNameLog;

class LifeNameLogScraper extends Controller
{
    private $url = "http://publicdata.onehouronelife.com/publicLifeLogData/lifeLog_bigserver2.onehouronelife.com/";
    private $storage_url = "logs/lifenames/";

    private $file_name_format = "Y_mF_d_l";
    private $file_type = ".txt";

    private $date_period;

    public function __construct()
    {
        $start = Carbon::now()->addDay();
        $end = Carbon::now()->subDays(1);

        $this->date_period = new \DatePeriod(
            new \DateTime($end->format('Y-m-d')),
            new \DateInterval('P1D'),
            new \DateTime($start->format('Y-m-d')),
       );
    }

    public function scrapeLog()
    {
        $time_start = microtime(true);
        Log::channel('sync')->info('LIFE NAME LOG scraper started');

        foreach($this->date_period as $key => $date)
        {
            $file_name = $this->getLogFileName($date->format($this->file_name_format));
            
            $this->downloadLog($file_name);
            $this->processLog($file_name);
            $this->deleteLog($file_name);
        }

        $time_end = microtime(true);
        $time = round(($time_end - $time_start), 3);

        Log::channel('sync')->info('LIFE NAME LOG scraper finished in: '.$time.' seconds');
    }

    private function downloadLog($file_name)
    {
        if(Storage::exists($this->storage_url.$file_name))
        {
            Log::channel('sync')->info("LIFE NAME LOG already exist: ".$file_name);
        }else
        {
            try 
            {
                $file = file_get_contents($this->getLogFileUrl($file_name));
                Storage::disk('local')->put($this->storage_url.$file_name, $file);
            } 
            catch (\Throwable $th) 
            {
                Log::channel('sync')->info("Could not download LIFE NAME LOG: ".$file_name);
                Log::channel('sync')->error($th);
            }
        }
    }

    public function processLog($file_name)
    {
        if(Storage::exists($this->storage_url.$file_name))
        {
            File::lines(Storage::path($this->storage_url.$file_name))->each(function ($line) {
                
                $line = explode(' ', $line, 2);
                //print_r($line);
                //print '<br/>';

                if(count($line) > 1)
                {
                    LifeNameLog::updateOrCreate(
                        [
                            'character_id' => $line[0],
                        ],
                        [
                            'name' => $line[1]
                        ]
                    );
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

    private function getLogFileName($date)
    {
        return $date.'_names'.$this->file_type;
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
