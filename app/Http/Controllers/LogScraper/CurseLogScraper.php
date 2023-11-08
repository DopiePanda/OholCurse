<?php

namespace App\Http\Controllers\LogScraper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Log;

use App\Models\CurseLog;

class CurseLogScraper extends Controller
{
    private $url = "http://publicdata.onehouronelife.com/publicLifeLogData/curseLog_bigserver2.onehouronelife.com/";
    private $storage_url = "logs/curses/";

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
        Log::channel('sync')->info('CURSE LOG scraper started');

        foreach($this->date_period as $key => $date)
        {
            $file_name = $this->getLogFileName($date->format($this->file_name_format));

            $this->downloadLog($file_name);
            $this->processLog($file_name);
            $this->deleteLog($file_name);
        }

        $time_end = microtime(true);
        $time = round(($time_end - $time_start), 3);

        Log::channel('sync')->info('CURSE LOG scraper finished in: '.$time.' seconds');
    }

    private function downloadLog($file_name)
    {
        if(Storage::exists($this->storage_url.$file_name))
        {
            Log::channel('sync')->info("CURSE LOG already exist: ".$file_name);
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
                
                $line = str_replace(' => ', ' ', $line);
                $line = explode(' ', $line);
                $type = $line[0];

                switch($type)
                {
                    case 'C':
                        $this->processCurseLine($line);
                        break;

                    case 'T':
                        $this->processTrustLine($line);
                        break;

                    case 'F':
                        $this->processForgiveLine($line);
                        break;

                    case 'A':
                        $this->processForgiveAllLine($line);
                        break;

                    case 'S':
                        $this->processScoreLine($line);
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

    private function processCurseLine($line)
    {
        CurseLog::updateOrCreate(
            [
                'timestamp' => $line[1],
                'character_id' => $line[2],
            ],
            [
                'type' => 'curse',
                'player_hash' => $line[3],
                'reciever_hash' => $line[4]
            ]
        );
    }

    private function processTrustLine($line)
    {
        CurseLog::updateOrCreate(
            [
                'timestamp' => $line[1],
                'character_id' => $line[2],
                'reciever_hash' => $line[4],
            ],
            [
                'type' => 'trust',
                'player_hash' => $line[3],
            ]
        );
    }

    private function processForgiveLine($line)
    {
        CurseLog::updateOrCreate(
            [
                'timestamp' => $line[1],
                'character_id' => $line[2],
                'reciever_hash' => $line[4],
            ],
            [
                'type' => 'forgive',
                'player_hash' => $line[3],
            ]
        );
    }

    private function processForgiveAllLine($line)
    {
        // Check if line is a forgive all command issued
        if(strlen($line[2]) < 40)
        {
            CurseLog::updateOrCreate(
                [
                    'timestamp' => $line[1],
                    'character_id' => $line[2],
                ],
                [
                    'type' => 'all',
                    'player_hash' => $line[3],
                ]
            );

        // Else it is a normal forgive starting with an A
        }else{
            CurseLog::updateOrCreate(
                [
                    'timestamp' => $line[1],
                    'reciever_hash' => $line[3],
                ],
                [
                    'type' => 'forgive',
                    'player_hash' => $line[2],
                ]
            );
        }
    }

    private function processScoreLine($line)
    {
        CurseLog::updateOrCreate(
            [
                'type' => 'score',
                'player_hash' => $line[2],
            ],
            [
                'timestamp' => $line[1],
                'curse_score' => $line[3],
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
