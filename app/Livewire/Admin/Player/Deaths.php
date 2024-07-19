<?php

namespace App\Livewire\Admin\Player;

use Livewire\Component;

use App\Models\LifeLog;

class Deaths extends Component
{
    public function render()
    {
        return view('livewire.admin.player.deaths');
    }

    public function deaths()
    {
        $ts = Carbon::now()->subMonth(1);

        $lives = LifeLog::distinct('died_to')
            ->where('type', 'death')
            ->where('timestamp', '>', $ts->timestamp)
            ->orderBy('player_hash')
            ->get();

        $sorted_lives = [];

        foreach($lives as $life)
        {
            if(array_key_exists($life->player_hash, $sorted_lives))
            {
                if(!in_array($life->died_to, $sorted_lives[$life->player_hash]))
                {
                    $sorted_lives[$life->player_hash][] = $life->died_to;
                }
            }
            else
            {
                $sorted_lives[$life->player_hash][] = $life->died_to;
            }
        }

        foreach($sorted_lives as $key => $value)
        {
            if(count($value) > 12)
            {
                print(count($value).": ".$key."<br />");

                foreach($value as $death)
                {
                    print("- ".$death."<br />");
                }
            }
        }
    }
}
