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
        $i = 0;

        File::lines(Storage::path($file))->each(function ($line) {
            
            if($i == 0)
            {
                $words = explode('=', $line);
                $output["id"] = $words[1];
            }

            if($i == 1)
            {
                $output["object"] = $line;
            }

            /*
                1. ID
                2. Name
                3. Containable
                4. Contain Size, Vert Slot Rotation
                5. Permanent, Min Pickup Age
                6. No Flip
                7. Side Access
                8. Held In Hand
                9. Blocks walking, Left blocking Radius, Right blocking radius, draw behind player
                10. Map chance = 0.000000#biomes_0,1,2
                11. Heat Value
                12. R Value
                13. Person, No Spawn
                14. Male
                15. Death Marker
                16. Home Marker
                17. Floor
                18. Floor Hugging
                19. Food Value
                20. Speed Multiplier
                21. Held Offset (0.000000,0.000000)
                22. Clothing
                23. Clothing offset  (0.000000,0.000000)
                24. Deadly Distnace
                25. Use Distance
                26. Sounds (1:0.0, -1:0.0, -1:0.0, -1:0.0)
                27. Creation Sound Initial Only
                28. Creation Sound Force
                29. Num Slots # Time Stretch
            */
            $i++;
        });
        
    }
}
