<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Log;

use App\Models\FirstName;
use App\Models\LastName;

class CharacterNameController extends Controller
{
    private $female_names = 'ohol/femaleNames.txt';
    private $male_names = 'ohol/maleNames.txt';
    private $last_names = 'ohol/lastNames.txt';

    private $type;

    public function importNames()
    {
        $this->processFile($this->female_names, 'female');
        $this->processFile($this->male_names, 'male');
        $this->processFile($this->last_names, 'last_name');
    }

    public function processFile($file, $type)
    {
        $start_time = microtime(true);
        $this->type = $type;

        if(Storage::exists($file))
        {
            
            try{
                ini_set('max_execution_time', 300);
                DB::beginTransaction();

                File::lines(Storage::path($file))->each(function ($line) 
                {
                    $this->updateOrCreateEntry($this->type, $line);
                });

                // Commit the DB transaction
                DB::commit();

                $end_time = microtime(true);
                $time = round(($end_time - $start_time), 3);

                Log::info("Game objects updated in $time seconds");

            }catch(\Exception $e) {
            
                // Rollback DB transaction
                DB::rollback();

                // Log exception message
                Log::error('Exception returned when inserting the NAMES');
                Log::error($e->getMessage());
            }
        }
    }

    private function updateOrCreateEntry($type, $line)
    {
        if($type == 'female')
        {
            FirstName::updateOrCreate(
                [
                    'name' => $line,
                ],
                [
                    'gender' => $type,
                ]
            );
        }

        if($type == 'male')
        {
            FirstName::updateOrCreate(
                [
                    'name' => $line,
                ],
                [
                    'gender' => $type,
                ]
            );
        }

        if($type == 'last_name')
        {
            LastName::updateOrCreate(
                [
                    'name' => $line,
                ],
                [
                ]
            );
        }
    }
}