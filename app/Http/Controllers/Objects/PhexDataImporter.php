<?php

namespace App\Http\Controllers\Objects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Log;

use App\Models\PhexHash;

class PhexDataImporter extends Controller
{
    private $file = 'ohol/phextags.txt';
    private $update_using = 'OLGC';

    public function parseFile()
    {
        $start_time = microtime(true);

        if(Storage::exists($this->file))
        {
            $players = Storage::json($this->file);

            try{
                ini_set('max_execution_time', 300);
                DB::beginTransaction();

                foreach($players as $player)
                {
                    if ($this->update_using == 'OLGC') 
                    {
                        $this->updateUsingOLGC($player);
                    }
                    elseif($this->update_using == 'PX')
                    {
                        $this->updateUsingPX($player);
                    }
                    else
                    {
                        Log::error('Invalid update search selection');
                    }
                }

                // Commit the DB transaction
                DB::commit();

                $end_time = microtime(true);
                $time = round(($end_time - $start_time), 3);

                Log::info("Phex users updated in $time seconds");

            }catch(\Exception $e) {
            
                // Rollback DB transaction
                DB::rollback();

                // Log exception message
                Log::error('Exception returned when inserting Phex users');
                Log::error($e->getMessage());
            }
        }
    }

    public function updateUsingOLGC($player)
    {
        $px_name = $player['PXname'];

        if ($px_name == 'None') 
        {
            $px_name = null;
        }

        PhexHash::updateOrCreate(
            [
                'olgc_hash' => $player['OLGChash'],
                'olgc_name' => $player['OLGCname'],
            ],
            [
                'px_hash' => $player['PXhash'],
                'px_name' => $px_name,
            ]
        );
    }

    public function updateUsingPX($player)
    {
        $px_name = $player['PXname'];

        if ($px_name == 'None') 
        {
            $px_name = null;
        }

        PhexHash::updateOrCreate(
            [
                'px_hash' => $player['PXhash'],
                'px_name' => $px_name,
            ],
            [
                'olgc_hash' => $player['OLGChash'],
                'olgc_name' => $player['OLGCname'],
            ]
        );
    }
}
