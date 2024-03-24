<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Badge;
use App\Models\ProfileBadge;
use App\Models\MapLog;

class ProcessNewBadges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-new-badges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $time = microtime(true);
        $this->methmanBadge();

        $end_time = microtime(true) - $time;
        $this->line("Completed in $end_time seconds");
    }

    public function methmanBadge()
    {
        $now = time();
        $two_days_ago = $now - 172800;

        $x_from = -100;
        $x_to = 100;

        $map_entries = MapLog::with('life:character_id,player_hash', 'name:character_id,name')
                        ->select('character_id', 'pos_x', 'pos_y', 'timestamp', 'object_id')
                        ->where('timestamp', '>=', $two_days_ago)
                        ->where('pos_x', '>=', $x_from)
                        ->where('pos_x', '<=', $x_to)
                        ->where('character_id', '!=', -1)
                        ->groupBy('character_id')
                        ->orderBy('timestamp', 'asc')
                        ->get();

        if(count($map_entries) > 0)
        {
            foreach($map_entries as $entry)
            {
                if($entry->object_id != 3053)
                {
                    ProfileBadge::updateOrCreate(
                        [
                            'player_hash' => $entry->life->player_hash,
                            'badge_id' => 1,
                        ], 
                        [
                            'achieved_at' => $entry->timestamp
                        ]
                    );
                }
            }
        }
        else
        {
            $this->line('No entried found');
        }
    }
}
