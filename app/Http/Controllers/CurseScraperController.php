<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Log;

use App\Models\CurseLog;


class CurseScraperController extends Controller
{
    private $url = "http://publicdata.onehouronelife.com/publicLifeLogData/curseLog_bigserver2.onehouronelife.com/";
    private $format = ".txt";

    private $date_range = '3';
    public $date_from;
    public $date_to;
    public $period;

    public function execute()
    {
        $this->formatDates();

        foreach ($this->period as $key => $value) {
            $date = $value->format("Y_mF_d_l");
            $this->storeLog($date);
            $this->processLog($date);
            $this->cleanLog($date);
        }
    }

    private function formatDates()
    {
        $to = Carbon::now();
        //$to->setTimezone('Europe/Oslo');
        $from = Carbon::now()->subDays($this->date_range);

        $this->period = new \DatePeriod(
            //new \DateTime('2023-07-13'),
            new \DateTime($from->format('Y-M-d')),
            new \DateInterval('P1D'),
            new \DateTime($to->format('Y-M-d')),
       );

    }

    private function storeLog($date)
    {
        $storage_url = 'logs/curses/'.$date.$this->format;
        if(Storage::exists($storage_url))
        {
            Log::info("File already exist: ".$date.$this->format);
        }else
        {
            $data = file_get_contents($this->url.$date.$this->format);
            Storage::disk('local')->put($storage_url, $data);
            Log::info("New log: ".$date.$this->format);
        }
    }

    private function processLog($date)
    {
        $storage_url = 'logs/curses/'.$date.$this->format;
        $fp = fopen(Storage::path($storage_url), "r");
        
        while(!feof($fp))
        {
            $lines = fgets($fp);
            $lines = explode('\n', $lines);

            foreach($lines as $line)
            {

                $single = explode(' ', $line);

                switch($single[0])
                {   
                    case 'C':
                        /*
                        The player curses someone

                        C 1689213552 6451957 d324804bb94c557cbb3977e45a7a40e36f8a8a52 => 329d5276db2cf6ca5e0c18a749690265b5a5a3ef
                        [0] = Type
                        [1] = Timestamp
                        [2] = Character ID
                        [3] = Player Hash
                        [4] = '=>'
                        [5] = Reciever Hash\n
                        */
                        if($single[0] == 'C')
                        {
                            $matches = [
                                'type' => 'curse',
                                'timestamp' => $single[1],
                                'character_id' => $single[2],
                                'player_hash' => $single[3]
                            ];

                            $values = [
                                'reciever_hash' => str_replace("\n","",$single[5])
                            ];

                            $this->storeLogToDB($matches, $values);

                            //Log::info("Action: Curse, Timestamp: $single[1] ".$single[3]);
                        }
                    case 'T':
                        /*
                        The player trusts someone

                        T 1689211353 6451780 203b31f07a319b55ad8255d250f46003014df2d3 => 1167309bf91acd71613d68cba71c47a65e4d90c6
                        [0] = Type
                        [1] = Timestamp
                        [2] = Character ID
                        [3] = Player Hash
                        [4] = '=>'
                        [5] = Reciever Hash\n
                        */
                        if($single[0] == 'T')
                        {
                            $matches = [
                                'type' => 'trust',
                                'timestamp' => $single[1],
                                'character_id' => $single[2],
                                'player_hash' => $single[3]
                            ];

                            $values = [
                                'reciever_hash' => str_replace("\n","",$single[5])
                            ];

                            $this->storeLogToDB($matches, $values);

                            //Log::info("Action: Trust, Timestamp: $single[1] ".$single[3]);
                        }
                    case 'A':
                        /*
                        The player forgives everyone

                        A 1689212391 cd1a3bef5cd0a625bac87b509fb87492cc29dc6a => 1a91c7424a46c34ebe422aff103460936e275890
                        [0] = Type
                        [1] = Timestamp
                        [2] = Player Hash
                        [3] = '=>'
                        [4] = Reciever Hash\n
                        */
                        if($single[0] == 'A')
                        {
                            $matches = [
                                'type' => 'all',
                                'timestamp' => $single[1],
                                'player_hash' => $single[2]
                            ];
                            if(isset($single[4]))
                            {
                                $values = [
                                    'reciever_hash' => str_replace("\n","",$single[4])
                                ];
                            }
                            else
                            {
                                $values = [
                                ];
                            }

                            $this->storeLogToDB($matches, $values);
                            //Log::info("Action: All forgiven, Timestamp: $single[1] ".$single[2]);
                        }
                    case 'F':
                        /*
                        The player forgives a single person

                        F 1689208608 6451729 389e0a48b68d35fe0a47cee322d6f421f173ef69 => f3cc5a16886cdfabc82a777efb6bb813d67c20d8
                        [0] = Type
                        [1] = Timestamp
                        [2] = Character ID
                        [3] = Player Hash
                        [4] = '=>'
                        [5] = Reciever Hash\n
                        */

                        // Check if player hash exists, if not this line is following a forgive all
                        if($single[0] == 'F')
                        {
                            if(strlen($single[3] <= 999))
                            {
                                $matches = [
                                    'type' => 'forgive',
                                    'timestamp' => $single[1],
                                    'character_id' => $single[2],
                                    'player_hash' => $single[3]
                                ];
        
                                $values = [
                                    'reciever_hash' => str_replace("\n","",$single[5])
                                ];
        
                                $this->storeLogToDB($matches, $values);

                                //Log::info("Action: Forgive, Timestamp: $single[1] ".$single[3]);
                            } else{
                                //Log::info("Action: Forgave all, Timestamp: $single[1]");                            
                            }
                        }

                    case 'S':
                        /*
                        The players total curse score

                        S 1689208608 f3cc5a16886cdfabc82a777efb6bb813d67c20d8 9
                        [0] = Type
                        [1] = Timestamp
                        [2] = Player Hash
                        [3] = Curse Score
                        */

                        // Check if curse score exists, if not, player has none
                        if($single[0] == 'S')
                        {
                            if(strlen($single[3] <= 999))
                            {
                                $matches = [
                                    'type' => 'score',
                                    'player_hash' => $single[2]
                                ];
        
                                $values = [
                                    'timestamp' => $single[1],
                                    'curse_score' => str_replace("\n","",$single[3])
                                ];
        
                                $this->storeLogToDB($matches, $values);
                            }else
                            {
                                //Log::info("Action: Score not found, Timestamp: $single[1]");
                            }
                        }
                        
                }
            }

        }

        fclose($fp);

        Log::info('Log processed and stored: '.$date.$this->format);
    }

    private function storeLogToDB(array $matches,  array $values)
    {
        $log = CurseLog::updateOrCreate(
            $matches,
            $values,
        );
    }

    private function cleanLog($date)
    {
        $storage_url = 'logs/curses/'.$date.$this->format;
        if(Storage::exists($storage_url))
        {
            Storage::delete($storage_url);
            Log::info('Log deleted: '.$date.$this->format);
        }
    }
}
