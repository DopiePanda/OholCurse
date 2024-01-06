<?php

namespace App\Livewire\Admin\Import;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Log;

use App\Models\GameObject;

class GameObjects extends Component
{
    use WithFileUploads;

    public $objects_file;

    public function mount()
    {
        
    }

    public function render()
    {
        return view('livewire.admin.import.game-objects');
    }

    public function process()
    {
        $this->validate([
            'objects_file' => 'required,mimetypes:text/plain',
        ]);

        $start_time = microtime(true);

        if($this->objects_file != null)
        {
            
            try{
                ini_set('max_execution_time', 300);
                DB::beginTransaction();

                File::lines($this->objects_file)->each(function ($line) {
                    
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
