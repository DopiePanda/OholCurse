<?php

namespace App\Http\Controllers\Logs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Log;

class ExportController extends Controller
{

    public $log_url = "http://publicdata.onehouronelife.com/publicLifeLogData/";
    public $date_period;
    public $file_extension = '.txt';

    protected $storage_path = 'logs/export/temp/';

    public $payload = [];

    public function index(Request $request)
    {
        $this->processRequest($request);
        return view('logs.export');
    }

    public function processRequest(Request $request)
    {
        $url = $this->getLogFileUrl('server9', 'lifeLog');
        $this->setDatePeriod('2023-08-12', '2023-08-26');

        foreach ($this->date_period as $key => $value) {
            $file = $this->getLogFileName($value);
            $this->storeLog($url, $file);
            $this->processLog($file);
            $this->cleanLog($file);
        }

        dd(array_filter($this->payload));
    }

    private function getLogFileUrl($server, $type)
    {
        return $this->log_url.$type.'_'.$server.'.onehouronelife.com/';
    }

    private function getLogFileName($date, $extra = null)
    {
        if($extra)
        {
            $file = $date->format("Y_mF_d_l").$extra.$this->file_extension;
        }else{
            $file = $date->format("Y_mF_d_l").$this->file_extension;
        }

        return $file;
    }

    private function setDatePeriod($from, $to)
    {
        $this->date_period = new \DatePeriod(
            new \DateTime($from),
            new \DateInterval('P1D'),
            new \DateTime($to),
       );
    }

    private function fileExists($url)
    {
        return str_contains(get_headers($url)[0], "200 OK");
    }

    private function storeLog($url, $file)
    {
        $storage_path = $this->storage_path.$file;

        if(Storage::exists($storage_path))
        {
            Log::info("File already exist: ".$file);
        }else
        {
            if($this->fileExists($url.$file))
            {
                $data = file_get_contents($url.$file);
                Storage::disk('local')->put($storage_path, $data);
                Log::info("New log: ".$file);
            }
            
        }
    }

    private function processLog($file)
    {
        $storage_path = $this->storage_path.$file;

        if(Storage::exists($storage_path))
        {
            File::lines(Storage::path($storage_path))->each(function ($line) {
                $this->parseLogLine($line, 'lifeLog');
            });
        }
    }

    private function parseLogLine($line, $log_type)
    {
        $line = rtrim($line);
        $values = explode(' ', $line);

        //dd($values);
        if($log_type == 'lifeLog')
        {
            $this->mapLifeLogValues($values);
        }elseif($log_type == 'lifeNameLog')
        {

        }elseif($log_type == 'curseLog')
        {
            
        }else
        {

        }
    }

    private function mapLifeLogValues($values)
    {
        $array = [];
        
        if($values[0] == 'B')
        {
            $coords = explode(',', trim($values[5], '()'));
            if($values[6] != 'noParent')
            {
                $parent = explode('=', $values[6])[1];
            }
            
            $pop = explode('=', $values[7])[1];
            $chain = explode('=', $values[8])[1];
            $race = explode('=', $values[9])[1];

            $array = [
                'type' => $values[0], 
                'timestamp' => $values[1], 
                'life_id' => $values[2], 
                'ehash' => $values[3], 
                'gender' => $values[4], 
                'pos_x' => $coords[0],
                'pos_y' => $coords[1], 
                'parent_id' => $parent ?? $values[6], 
                'pop' => $pop,
                'chain' => $chain,
                'race' => $race,
            ];
        }elseif($values[0] == 'D')
        {
            //dd($values);
            $age = explode('=', $values[4])[1];
            $coords = explode(',', trim($values[6], '()'));
            $pop = explode('=', $values[8])[1];

            $array = [
                'type' => $values[0], 
                'timestamp' => $values[1], 
                'life_id' => $values[2], 
                'ehash' => $values[3], 
                'age' => $age,
                'gender' => $values[5], 
                'pos_x' => $coords[0],
                'pos_y' => $coords[1], 
                'death' => $values[7], 
                'pop' => $pop,
            ];
        }

        array_push($this->payload, $array);

    }

    private function cleanLog($file)
    {
        $storage_path = $this->storage_path.$file;

        if(Storage::exists($storage_path))
        {
            Storage::delete($storage_path);
            Log::info('Log deleted: '.$file);
        }

        /*
        if(Storage::exists($storage_url_logs))
        {
            Storage::delete($storage_url_logs);
            Log::info('Log deleted: '.$date.$this->format);
        }
        */
    }
}