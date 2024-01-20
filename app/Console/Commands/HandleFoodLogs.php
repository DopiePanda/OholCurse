<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\LazyCollection;
use Carbon\Carbon;
use Log;
use DB;

use App\Models\FoodLog;

class HandleFoodLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:handle-food-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $url = "http://publicdata.onehouronelife.com/foodLogDetails/foodLogDetail_bigserver2.onehouronelife.com/";
    private $storage_url = "logs/foodlogs/";

    private $file_name_format = "Y_mF_d_l";
    private $file_type = ".txt";

    private $date_period;

    protected $start_time;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setDatePeriod();
        $this->scrapeLog();
    }

    public function setDatePeriod()
    {
        $start = Carbon::now()->subDays(0);
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
        //Log::channel('sync')->info('FOOD DETAIL LOG scraper started');

        foreach($this->date_period as $key => $date)
        {
            $this->start_time = microtime(true);
            $file_name = $this->getLogFileName($date->format($this->file_name_format));
            
            $this->downloadLog($file_name);
            $this->processLog($file_name);
            $this->deleteLog($file_name);
        }

        $time_end = microtime(true);
        $time = round(($time_end - $time_start), 3);
        Log::channel('sync')->info('FOOD DETAIL LOG scraper finished in: '.$time.' seconds');
    }

    private function downloadLog($file_name)
    {
        if(Storage::exists($this->storage_url.$file_name))
        {
            //Log::channel('sync')->info("FOOD DETAIL LOG already exist: ".$file_name);
        }else
        {
            $file_headers = @get_headers($this->getLogFileUrl($file_name));

            if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                //Log::channel('sync')->info("FOOD DETAIL LOG does not exist: ".$file_name);
            }
            else 
            {
                $file = file_get_contents($this->getLogFileUrl($file_name));
                Storage::disk('local')->put($this->storage_url.$file_name, $file);
                //Log::channel('sync')->info("FOOD DETAIL LOG downloaded: ".$file_name);
            }
        }

        $time = round((microtime(true) - $this->start_time), 3);
        Log::channel('sync')->info("FOOD DETAIL LOG download finished after $time seconds");
    }

    public function processLog($file_name)
    {
        if(Storage::exists($this->storage_url.$file_name))
        {

            $path = $this->storage_url.$file_name;

            LazyCollection::make(function () use ($path) {
                $handle = fopen(Storage::path($path), 'r');
             
                while (($line = fgets($handle)) !== false) {
                    yield $line;
                }
            })->chunk(500)->each(function ($lines) {
                foreach ($lines as $line) {
                    $data = explode(' ', rtrim($line));

                    if(count($data) == 3)
                    {
                        DB::table('food_logs')
                            ->updateOrInsert(
                                ['timestamp' => $data[0], 'character_id' => $data[1]],
                                ['object_id' => $data[2]]
                            );
                    }
                }
            });

            $time = round((microtime(true) - $this->start_time), 3);
            Log::channel('sync')->info("FOOD DETAIL LOG processing finished after $time seconds");
        }
    }

    private function deleteLog($file_name)
    {
        if(Storage::exists($this->storage_url.$file_name))
        {
            Storage::delete($this->storage_url.$file_name);
            //Log::channel('sync')->info("FOOD DETAIL LOG deleted: ".$file_name);
        }

        $time = round((microtime(true) - $this->start_time), 3);
        Log::channel('sync')->info("FOOD DETAIL LOG deleted after $time seconds");
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
}
