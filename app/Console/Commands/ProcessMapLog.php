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
    protected $signature = 'app:process-map-log';

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
            $this->log("Map log already stored to DB.");
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
        $client = new Client();
    
        $website = $client->request('GET', $this->url);
        
        $links = $website->filter('a')->each(function ($node) {
            return $node->text();
        });

        $this->file_name = array_pop($links);

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

        if(Storage::exists($this->getFilePath()))
        {
            
            $payload = [];
            $path = $this->getFilePath();

            LazyCollection::make(function () use ($path) {
                $handle = fopen(Storage::path($path), 'r');
             
                while (($line = fgets($handle)) !== false) {
                    yield $line;
                }
            })->chunk(1000)->each(function ($lines) {
                foreach ($lines as $line) {
                    $array = explode(' ', rtrim($line));

                    if(count($array) == 2)
                    {
                        $this->timestamp_start = $array[1];
                    }else
                    {
                        $object = explode('u', $line[3]);
                        $now = date("Y-m-d H:i:s");
                        $payload[] = [
                            'timestamp' => ($this->timestamp_start + $array[0]),
                            'pos_x'=> $array[1],
                            'pos_y'=> $array[2],
                            'object_id'=> $object[0],
                            'use'=> $object[1] ?? null,
                            'character_id'=> $array[4],
                            'updated_at' => $now,
                            'created_at' => $now
                        ];
                    }
                }
                
                try{
                    DB::disableQueryLog();
                    DB::beginTransaction();
    
                    DB::table('map_logs')
                            ->insert(
                                $payload
                            );
    
                    // Commit the DB transaction
                    DB::commit();
    
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
