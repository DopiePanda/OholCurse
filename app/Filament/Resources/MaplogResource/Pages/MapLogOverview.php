<?php

namespace App\Filament\Resources\MaplogResource\Pages;

use App\Filament\Resources\MaplogResource;
use Filament\Resources\Pages\Page;

use Goutte\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use Carbon\Carbon;
use ZipArchive;
use Log;

use App\Models\Cronjob;

class MapLogOverview extends Page
{
    protected static string $resource = MaplogResource::class;

    protected static string $view = 'filament.resources.maplog-resource.pages.map-log-overview';

    protected static ?string $navigationLabel = 'Map Log Overview';
    protected static ?string $navigationIcon = 'heroicon-s-book-open';
    protected static ?string $navigationParentItem = 'Map Logs';
    
    protected static ?string $navigationGroup = 'Logs';

    protected $url = "http://publicdata.onehouronelife.com/publicMapChangeData/bigserver2.onehouronelife.com/";
    protected $file_name;
    protected $storage_path = "logs/map/";

    public $time_start;
    public $timestamp_start;

    public $logs;

    public function mount(): void
    {
        static::authorizeResourceAccess();
        $this->getPublicMapLogs();
    }

    public function download($file_name)
    {
        // Timestamp for measuring performance
        $this->time_start = microtime(true);

        // Query to check if the map log already have been processed
        $cron = DB::table('cronjobs')
                ->where('module', 'maplog')
                ->where('file_name', $file_name)
                ->latest()
                ->first();
        
        if($cron)
        {
            $this->log("Map log already stored to DB.");
            return false;
        }else
        {
            $this->file_name = $file_name;

            ini_set('memory_limit', '512M');
            $this->store();
            $this->process();
            $this->archive();
            $this->logResult();
        }
    }

    public function getPublicMapLogs()
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
        $file_names = array_slice(array_reverse($links), 0, 16);

        //dd($file_names);

        $cronjobs = Cronjob::select('completed_at', 'time_elapsed', 'file_name')
                    ->limit(20)
                    ->orderBy('file_name', 'desc')
                    ->get()
                    ->toArray();


        $logs = [];

        foreach ($cronjobs as $job) 
        {
            $logs[] = [
                'file_name' => $job['file_name'],
                'completed_at' => $job['completed_at'],
                'time_elapsed' => $job['time_elapsed'],
                'found' => true,
            ];  
        }

        $files = array_column($logs, 'file_name');
        //dd($files);

        foreach ($file_names as $file) 
        {
            
            $found_key = array_search($file, $files);


            if ($found_key == 0) 
            {
                //dd($file);
                $logs[] = [
                    'file_name' => $file,
                    'completed_at' => null,
                    'time_elapsed' => null,
                    'found' => false,
                ]; 
            }else
            {
                //dd($file);
            }     
        }

        $file_name  = array_column($logs, 'file_name');
        $completed_at = array_column($logs, 'completed_at');

        // Sort the data with volume descending, edition ascending
        // Add $data as the last parameter, to sort by the common key
        array_multisort($file_name, SORT_DESC, $logs);
        array_shift($logs);

        //dd($logs);

        $this->logs = $logs;
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
            })->chunk(100)->each(function ($lines) {
                foreach ($lines as $line) {
                    $array = explode(' ', $line);
        
                    if(count($array) == 2)
                    {
                        $this->timestamp_start = $array[1];
                    }else
                    {
                        $object = explode('u', $array[3]);

                        $payload[] = [
                            'timestamp' => ($this->timestamp_start + $array[0]),
                            'pos_x'=> $array[1],
                            'pos_y'=> $array[2],
                            'object_id'=> $object[0],
                            'use'=> $object[1] ?? null,
                            'character_id'=> $array[4],
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

        $this->getPublicMapLogs();
    }
}
