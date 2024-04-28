<?php

namespace App\Livewire\Tools;

use Livewire\Component;

class ObjectInspector extends Component
{

    private $jasonAuthorHash = '2BE17D1AC5';

    public $output = [];

    public function mount()
    {

    }

    public function render()
    {
        return view('livewire.tools.object-inspector');
    }

    private function processLog()
    {
        $output = [];
        File::lines(Storage::path($file))->each(function ($line) {
            
            $new_lines = explode(',', $line);
            foreach($new_lines as $l)
            {
                $pairs = explode('=', $l);
                $type = $pairs[0];

                switch($type)
                {
                    case 'spriteID':
                        $this->processCurseLine($line);
                        break;

                    default:
                    $this->output[$pairs[0]] = $pairs[1];
                }

                
            }

        });
        
    }
}
