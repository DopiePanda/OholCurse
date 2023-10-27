<?php

namespace App\Http\Controllers\Objects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Log;

use App\Models\GameObject;


class GameObjectImporter extends Controller
{
    private $file = 'ohol/objects.txt';

    public function parseObjectsFile()
    {
        $start_time = microtime(true);

        if(Storage::exists($this->file))
        {
            
            try{
                ini_set('max_execution_time', 300);
                DB::beginTransaction();

                File::lines(Storage::path($this->file))->each(function ($line) {
                    
                    $line = explode(' ', $line, 2);

                    if(count($line) == 2)
                    {
                        GameObject::updateOrCreate(
                            [
                                'id' => $line[0],
                            ],
                            [
                                'name' => $line[1],
                            ]
                        );
                    }
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
                Log::error('Exception returned when inserting the GAME OBJECTS');
                Log::error($e->getMessage());
            }
        }
    }
}
