<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Log;

use App\Models\LifeLog;
use App\Models\LifeNameLog;

class LifeScraperController extends Controller
{
    private $url = "http://publicdata.onehouronelife.com/publicLifeLogData/lifeLog_bigserver2.onehouronelife.com/";
    private $format = ".txt";

    private $date_range = '2';
    public $date_from;
    public $date_to;
    public $period;

    public function execute($from = null, $to = null)
    {
        ini_set('max_execution_time', 0);
        
        if($from)
        {
            $this->period = new \DatePeriod(
                new \DateTime($from),
                new \DateInterval('P1D'),
                new \DateTime($to),
           );
        }else{
            $this->formatDates();
        }

        foreach ($this->period as $key => $value) {
            $date = $value->format("Y_mF_d_l");
            $this->storeLog($date);
            $this->processLog($date);
            $this->cleanLog($date);
        }

        //print("<h1>Execution finished for date: $from</h1>");
    }

    private function formatDates()
    {
        $to = Carbon::now();
        //$to->setTimezone('Europe/Oslo');
        $from = Carbon::now()->subDays($this->date_range);

        $this->period = new \DatePeriod(
            new \DateTime($from->format('Y-M-d')),
            new \DateInterval('P1D'),
            new \DateTime($to->format('Y-M-d')),
       );

    }

    private function storeLog($date)
    {
        $storage_url_names = 'logs/lifes/'.$date.'_names'.$this->format;

        if(Storage::exists($storage_url_names))
        {
            Log::info("File already exist: ".$date.'_names'.$this->format);
        }else
        {
            $data = file_get_contents($this->url.$date.'_names'.$this->format);
            Storage::disk('local')->put($storage_url_names, $data);
            Log::info("New log: ".$date.'_names'.$this->format);
        }

        $storage_url_logs = 'logs/lifes/'.$date.$this->format;

        if(Storage::exists($storage_url_logs))
        {
            Log::info("File already exist: ".$date.$this->format);
        }else
        {
            $data = file_get_contents($this->url.$date.$this->format);
            Storage::disk('local')->put($storage_url_logs, $data);
            Log::info("New log: ".$date.$this->format);
        }
    }

    private function processLog($date)
    {
        $storage_url_logs = 'logs/lifes/'.$date.$this->format;
        $fp = fopen(Storage::path($storage_url_logs), "r");
        
        while(!feof($fp))
        {
            $lines = fgets($fp);
            $lines = explode('\n', $lines);

            foreach($lines as $line)
            {
                // Break full line up into words
                $single = explode(' ', $line);

                switch($single[0])
                {   
                    case 'B':
                        /*
                        New Player Birth

                        B 1691549963 6545870 c3dd3e9c19500c0834b2606491e7541becaee82a F (-453804,-78) parent=6545815 pop=47 chain=7
                        [0] = Type
                        [1] = Timestamp
                        [2] = Character ID
                        [3] = Player Hash
                        [4] = Gender
                        [5] = X/Y Location
                        [6] = 'parent=6545815'
                        [7] = 'pop=47'
                        [8] = 'chain=7'
                        [9] = 'race=A/C/F/D' [Darkest, brown, ginger, white]
                        */
                        if($single[0] == 'B')
                        {
                            $matches = [
                                'type' => 'birth',
                                'timestamp' => $single[1],
                                'character_id' => $single[2],
                                'player_hash' => $single[3]
                            ];

                            if($single[4] == 'F')
                            {
                                $gender = 'female';
                            }else{
                                $gender = 'male';
                            }

                            $coords = trim($single[5], '()');

                            if($single[6] != 'noParent')
                            {
                                $parent = explode('=', $single[6]);
                                $parent = $parent[1];
                            }else{
                                $parent = null;
                            }

                            // Extract population, yum_chain and family_type
                            $pop = explode('=', $single[7]);
                            $yum_chain = explode('=', $single[8]);
                            
                            if(isset($single[9]))
                            {
                                $family_type = explode('=', $single[9]);
                                $family_type = rtrim($family_type[1]);
                            }
                            
                            // [9] = 'race=A/C/F/D' [Darkest, brown, ginger, white]
                            switch($family_type)
                            {
                                case 'A':
                                    $family_type = 'desert';
                                    break;
                                case 'C':
                                    $family_type = 'jungle';
                                    break;
                                case 'F':
                                    $family_type = 'arctic';
                                    break;
                                case 'D':
                                    $family_type = 'language';
                                    break;
                            }

                            $values = [
                                'gender' => $gender,
                                'location' => $coords,
                                'parent_id' => $parent,
                                'population' => $pop[1],
                                'yum_chain' => str_replace("\n","",$yum_chain[1]),
                                'family_type' => $family_type ?? 'missing',
                            ];

                            $this->storeLifeToDB($matches, $values);

                            //Log::info("Action: Curse, Timestamp: $single[1] ".$single[3]);
                        }
                    case 'D':
                        /*
                        New player death

                        D 1691551831 6545870 c3dd3e9c19500c0834b2606491e7541becaee82a age=31.14 F (-453741,-99) Sleepy_Grizzly_Bear pop=44
                        [0] = Type
                        [1] = Timestamp
                        [2] = Character ID
                        [3] = Player Hash
                        [4] = 'age=31.14'
                        [5] = Gender
                        [6] = X/Y Location
                        [7] = Cause of death
                        [8] = 'pop=44'
                        */
                        if($single[0] == 'D')
                        {
                            $matches = [
                                'type' => 'death',
                                'timestamp' => $single[1],
                                'character_id' => $single[2],
                                'player_hash' => $single[3]
                            ];

                            if($single[5] == 'F')
                            {
                                $gender = 'female';
                            }else{
                                $gender = 'male';
                            }

                            $age = explode('=', $single[4]);
                            $coords = trim($single[6], '()');
                            $pop = explode('=', $single[8]);

                            $values = [
                                'age' => $age[1],
                                'gender' => $gender,
                                'location' => $coords,
                                'died_to' => $single[7],
                                'population' => str_replace("\n","",$pop[1])
                            ];

                            $this->storeLifeToDB($matches, $values);

                            //Log::info("Action: Trust, Timestamp: $single[1] ".$single[3]);
                        }
                    
                        
                }
            }

        }

        fclose($fp);

        Log::info('Log processed and stored: '.$date.$this->format);

        $storage_url_names = 'logs/lifes/'.$date.'_names'.$this->format;

        $fp = fopen(Storage::path($storage_url_names), "r");
        
        while(!feof($fp))
        {
            $lines = fgets($fp);
            $lines = explode('\n', $lines);



            foreach($lines as $line)
            {
                // Break full line up into words
                // 6405652 BUTTERFLY JON
                $line = rtrim($line);
                $single = explode(' ', $line);

                // [0] = Character ID
                // [1] = First Name
                // [2] = Last name
                if(count($single) == 2)
                {
                    //dd($single);
                    $id = $single[0];
                    $first_name = $single[1];  
                    $last_name = '';
                }elseif(count($single) == 1){
                    $id = $single[0];
                    $first_name = 'na';
                    $last_name = '';
                }else{
                        $id = $single[0];
                        $first_name = $single[1];
                        $last_name = $single[2];
                }

                $name = $first_name.' '.$last_name;

                $matches = [
                    'character_id' => $id
                ];     

                $values = [
                    'name' =>  $name,
                ];

                $this->storeLifeNameToDB($matches, $values);

                //Log::info("Action: Curse, Timestamp: $single[1] ".$single[3]);

            }

        }

        fclose($fp);

        Log::info('Log processed and stored: '.$date.'_names'.$this->format);
    }

    private function storeLifeToDB(array $matches,  array $values)
    {

        $log = LifeLog::updateOrCreate(
            $matches,
            $values,
        );
    }

    private function storeLifeNameToDB(array $matches,  array $values)
    {
        $log = LifeNameLog::updateOrCreate(
            $matches,
            $values,
        );
    }

    private function cleanLog($date)
    {
        $storage_url_logs = 'logs/lifes/'.$date.$this->format;
        $storage_url_names = 'logs/lifes/'.$date.'_names'.$this->format;

        if(Storage::exists($storage_url_names))
        {
            Storage::delete($storage_url_names);
            Log::info('Log deleted: '.$date.'_names'.$this->format);
        }

        if(Storage::exists($storage_url_logs))
        {
            Storage::delete($storage_url_logs);
            Log::info('Log deleted: '.$date.$this->format);
        }
    }

    public function debug()
    {
        ini_set('max_execution_time', 0);
        
        // Days done: 4
        $value = Carbon::now();

        $date = $value->format("Y_mF_d_l");
        $this->storeLog($date);
        $this->processLog($date);
        $this->cleanLog($date);
    }
}
