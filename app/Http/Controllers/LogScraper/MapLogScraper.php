<?php

namespace App\Http\Controllers\LogScraper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Goutte\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Log;

class MapLogScraper extends Controller
{
    private $url = "http://publicdata.onehouronelife.com/publicMapChangeData/bigserver2.onehouronelife.com/";
    private $storage_url = "logs/map/";
    private $file_suffix = "time_mapLog.txt";

    private $file_name;
    public $time_start;

    public function __construct()
    {

    }

    public function scrapeLog($offset = null)
    {
        $time_start = microtime(true);

        $this->downloadLog($offset);
        $this->processLog($this->getLatestMapLog($offset));

        $time_end = microtime(true);
        $time = round(($time_end - $time_start), 3);
        Log::channel('sync')->info('MAP LOG scraper finished in: '.$time.' seconds');
    }

    public function processLog($file_name)
    {
        if(Storage::exists($this->storage_url.$file_name))
        {
            Log::channel('sync')->info('BEGIN MAP LOG TRANSACTION FOR: '.$file_name);

            try{
                ini_set('max_execution_time', 300);
                DB::beginTransaction();

                File::lines(Storage::path($this->storage_url.$file_name))->each(function ($line) {
                    
                    $line = explode(' ', $line);

                    if(count($line) == 2)
                    {
                        $this->time_start = $line[1];
                    }else
                    {
                        if(count($line) == 5 && $line[1] < 0)
                        {
                            $object = explode('u', $line[3]);

                            DB::table('map_logs')->insert([
                                'timestamp' => ($this->time_start+$line[0]),
                                'character_id' => $line[4],
                                'pos_x' => $line[1],
                                'pos_y' => $line[2],
                                'object_id' => $object[0],
                                'use' => $object[1] ?? null,
                            ]);
                        }
                    }
                });

                // Commit the DB transaction
                DB::commit();

                Log::channel('sync')->info('COMPLETED MAP LOG TRANSACTION FOR: '.$file_name);

            }catch(\Exception $e) {
            
                // Rollback DB transaction
                DB::rollback();

                // Log exception message
                Log::channel('sync')->error('Exception returned during database transaction for: '.$file_name);
                Log::channel('sync')->error($e->getMessage());
            }
        }
    }

    public function downloadLog($offset = null)
    {
        $file_name = $this->getLatestMapLog($offset);

        if(Storage::exists($this->storage_url.$file_name))
        {
            Log::channel('sync')->info("MAP LOG already exist: ".$file_name);
        }else
        {
            $data = file_get_contents($this->url.$file_name);

            Storage::disk('local')->put($this->storage_url.$file_name, $data);
            Log::channel('sync')->info("New MAP LOG downloaded: ".$file_name);
        }
    }

    public function getLatestMapLog($offset = null)
    {
        $client = new Client();
    
        $website = $client->request('GET', $this->url);
        
        $links = $website->filter('a')->each(function ($node) {
            return $node->text();
        });

        if($offset)
        {
            $i = 0;
            while($i < $offset)
            {
                array_pop($links);
                $i++;
            }

            return array_pop($links);
        }else
        {
            return array_pop($links);
        }
    }       
}
