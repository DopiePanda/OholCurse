<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Log;

use App\Models\FoodLog;

class ProcessFoodLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $url = "http://publicdata.onehouronelife.com/foodLogDetails/foodLogDetail_bigserver2.onehouronelife.com/";
    private $storage_url = "logs/foodlogs/";

    private $file_name_format = "Y_mF_d_l";
    private $file_type = ".txt";

    private $date_period;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public FoodLog $log,
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }

    public function setDatePeriod()
    {
        $start = Carbon::now()->subDays(4);
        $end = Carbon::now()->subDays(10);

        $this->date_period = new \DatePeriod(
            new \DateTime($end->format('Y-m-d')),
            new \DateInterval('P1D'),
            new \DateTime($start->format('Y-m-d')),
       );
    }

    public function scrapeLog()
    {
        $time_start = microtime(true);
        Log::channel('sync')->info('FOOD DETAIL LOG scraper started');

        foreach($this->date_period as $key => $date)
        {
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
            Log::channel('sync')->info("FOOD DETAIL LOG already exist: ".$file_name);
        }else
        {
            $file_headers = @get_headers($this->getLogFileUrl($file_name));

            if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                Log::channel('sync')->info("FOOD DETAIL LOG does not exist: ".$file_name);
            }
            else 
            {
                $file = file_get_contents($this->getLogFileUrl($file_name));
                Storage::disk('local')->put($this->storage_url.$file_name, $file);
                Log::channel('sync')->info("FOOD DETAIL LOG downloaded: ".$file_name);
            }
        }
    }

    public function processLog($file_name)
    {
        if(Storage::exists($this->storage_url.$file_name))
        {
            /*
            File::lines(Storage::path($this->storage_url.$file_name))->each(function ($line) {
                
                $line = explode(' ', $line);
                //print_r($line);
                //print '<br/>';

                if(count($line) == 3)
                {
                    FoodLog::updateOrCreate(
                        [
                            'timestamp' => $line[0],
                            'character_id' => $line[1],
                        ],
                        [
                            'object_id' => $line[2]
                        ]
                    );
                }
            });
            */

            LazyCollection::make(function () {
                $handle = fopen(Storage::path($this->storage_url.$file_name), 'r');
             
                while (($line = fgets($handle)) !== false) {
                    yield $line;
                }
            })->chunk(4)->map(function (array $lines) {
                return FoodLog::fromLines($lines);
            })->each(function (FoodLog $logEntry) {
                dd($logEntry);
            });

            Log::channel('sync')->info("FOOD DETAIL LOG processed: ".$file_name);
        }
    }

    private function deleteLog($file_name)
    {
        if(Storage::exists($this->storage_url.$file_name))
        {
            Storage::delete($this->storage_url.$file_name);
            Log::channel('sync')->info("FOOD DETAIL LOG deleted: ".$file_name);
        }
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
