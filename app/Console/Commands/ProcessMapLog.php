<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use Carbon\Carbon;
use ZipArchive;
use Log;

class ProcessMapLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-map-log {index=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $url = "http://publicdata.onehouronelife.com/publicMapChangeData/bigserver2.onehouronelife.com/";
    protected $file_name;
    protected $storage_path = "logs/map/";

    public $time_start;
    public $timestamp_start;

    public $payload;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Timestamp for measuring performance
        $this->time_start = microtime(true);

        // Scrapes the map log URL and assigns the file name to $file_name
        $this->getLatestLog();

        // Query to check if the map log already have been processed
        $cron = DB::table('cronjobs')
                ->where('module', 'maplog')
                ->where('file_name', $this->file_name)
                ->latest()
                ->first();
        
        if($cron)
        {
            //$this->log("Map log already stored to DB.");
            return false;
        }else
        {
            ini_set('memory_limit', '512M');
            $this->store();
            $this->process();
            $this->archive();
            $this->logResult();
        }
    }

    public function getLatestLog()
    {
        // Instanciate Goutte web scraper client
        $client = new Client();
    
        // Fetch the public map log page
        $website = $client->request('GET', $this->url);
        
        // Filter out all of the links and return the link names as an array (ex. 1703174827time_mapLog.txt)
        $links = $website->filter('a')->each(function ($node) {
            return $node->text();
        });

        // Remove all links that are not map logs and reverse the array to get newest log at top
        $links = array_slice(array_reverse($links), 0, 15);

        // Get the index of the log that should be downloaded. Defaults to 0
        $index = $this->argument('index');

        // Set the log file name based on the provided index
        $this->file_name = $links[$index];

        // Unset Goutte client and varibles for memory
        unset($client);
        unset($website);
        unset($links);
    }

    public function getFilePath()
    {
        return $this->storage_path.$this->file_name;
    }

    public function getFileUrl()
    {
        return $this->url.$this->file_name;
    }

    public function store()
    {
        $zip_file_name = str_replace('.txt', '.zip', $this->file_name);

        if(Storage::exists($this->getFilePath()) && !Storage::exists($this->storage_path.$zip_file_name))
        {
            return $this->log("MAP LOG already exist.");
        }
        elseif(!Storage::exists($this->getFilePath()) && Storage::exists($this->storage_path.$zip_file_name))
        {
            return;
        }
        else
        {
            $file_headers = @get_headers($this->getFileUrl());
            

            if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                $url = $this->getFileUrl();
                $this->log("MAP LOG could not be located on url: $url.");
            }
            else 
            {
                $file = file_get_contents($this->url.$this->file_name);
                Storage::disk('local')->put($this->getFilePath(), $file);
                $this->log("MAP LOG downloaded.");
            }

        }
    }

    public function process()
    {

        //dd("Starting processing");

        if(Storage::exists($this->getFilePath()))
        {
            
            $this->payload = [];
            $path = $this->getFilePath();

            LazyCollection::make(function () use ($path) {
                $handle = fopen(Storage::path($path), 'r');
             
                while (($line = fgets($handle)) !== false) {
                    yield $line;
                }
            })->chunk(100)->each(function ($lines) {
                foreach ($lines as $line) {
                    $array = explode(' ', $line);
        
                    if(count($array) == 2)
                    {
                        $this->timestamp_start = $array[1];
                    }else
                    {
                        $object = explode('u', $array[3]);

                        if($object[0] != 558 && $object[0] != 2758 && $object[0] != 2760)
                        {
                            if($array[1] < 50000 && $array[1] > -10000000)
                            {
                                $this->payload[] = [
                                    'timestamp' => ($this->timestamp_start + $array[0]),
                                    'pos_x'=> $array[1],
                                    'pos_y'=> $array[2],
                                    'object_id'=> $object[0],
                                    'use'=> $object[1] ?? null,
                                    'character_id'=> $array[4],
                                ];
                            }

                            if($array[1] > 500000)
                            {
                                if($array[4] != -1 && $array[4] != 0)
                                {
                                    $this->payload[] = [
                                        'timestamp' => ($this->timestamp_start + $array[0]),
                                        'pos_x'=> $array[1],
                                        'pos_y'=> $array[2],
                                        'object_id'=> $object[0],
                                        'use'=> $object[1] ?? null,
                                        'character_id'=> $array[4],
                                    ];
                                };
                            }
                        }
                    }
                }

                try{
                    DB::disableQueryLog();
                    DB::beginTransaction();

                    DB::table('map_logs')
                            ->insert(
                                $this->payload
                            );

                    // Commit the DB transaction
                    DB::commit();

                    $this->payload = [];

                }catch(\Exception $e) {
                
                    // Rollback DB transaction
                    DB::rollback();

                    // Log exception message
                    $this->log("Error ocurred.");
                    Log::channel('sync')->error($e->getMessage());
                }
            });

            

            $this->log("Processing complete.");
        }
    }

    public function archive()
    {
        $zip_file_name = str_replace('.txt', '.zip', $this->file_name);

        if(Storage::exists($this->getFilePath()) && !Storage::exists($this->storage_path.$zip_file_name))
        {
            $zip = new ZipArchive;

            if ($zip->open(Storage::path($this->storage_path.$zip_file_name), ZipArchive::CREATE) === TRUE) 
            {
                $filesToZip = [
                    Storage::path($this->storage_path.$this->file_name),
                ];

                foreach ($filesToZip as $file) 
                {
                    $zip->addFile($file, basename($file));
                }

                $zip->close();

                if(Storage::exists($this->getFilePath()) && Storage::exists($this->storage_path.$zip_file_name))
                {
                    Storage::delete($this->getFilePath());
                    $this->log("Map log zipped and archived.");
                }

            }
            else
            {
                return "Failed to create the zip file.";
            }
        }
        elseif(!Storage::exists($this->getFilePath()) && Storage::exists($this->storage_path.$zip_file_name))
        {
            $this->log("Map log already archived.");
        }
    }

    public function log($msg, $time = true, $channel = 'sync')
    {
        if($time)
        {
            $time = round((microtime(true) - $this->time_start), 3);
            $msg .= " Time: $time seconds.";
        }

        $msg .= " File: $this->file_name";

        Log::channel($channel)->info($msg);
    }

    public function logResult()
    {
        $now = date("Y-m-d H:i:s");
        DB::table('cronjobs')
            ->insert([
                'type' => 'scraping',
                'module' => 'maplog',
                'completed_at' => time(),
                'time_elapsed' => round(microtime(true) - $this->time_start),
                'file_name' => $this->file_name,
                'url' => $this->url,
                'storage_path' => $this->storage_path,
                'updated_at' => $now,
                'created_at' => $now
            ]);
    }
}
