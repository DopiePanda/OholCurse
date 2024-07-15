<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Badge;
use App\Models\ProfileBadge;
use App\Models\MapLog;
use App\Models\CurseLog;

use Log;

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

    private $now;
    private $two_days_ago;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $time = microtime(true);

        $this->now = time();
        $this->two_days_ago = $this->now - 172800;

        $this->methmanBadge(11);
        $this->trustJasonBadge(12);
        $this->curseJasonBadge(4);

        $end_time = microtime(true) - $time;
        $this->line("Completed in $end_time seconds");
    }

    public function methmanBadge($badge_id)
    {
        $this->badge_id = $badge_id;

        $x_from = -100;
        $x_to = 100;

        $map_entries = MapLog::with('life:character_id,player_hash', 'name:character_id,name')
                        ->select('character_id', 'pos_x', 'pos_y', 'timestamp', 'object_id')
                        ->where('timestamp', '>=', $this->two_days_ago)
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
                // Check that it's not a baby bone pile from a person doing /die
                // Since that gets logged as a player interaction with that object in the map logs

                if($entry->object_id != 3053 && $entry->life)
                {
                    ProfileBadge::updateOrCreate(
                        [
                            'player_hash' => $entry->life->player_hash,
                            'badge_id' => $this->badge_id,
                        ], 
                        [
                            'achieved_at' => $entry->timestamp
                        ]
                    );

                    Log::channel('sync')->info($entry->life->player_hash." got badge ".$badge_id);
                }
            }
        }
        else
        {
            $this->line('No entried found');
        }
    }

    public function curseJasonBadge($badge_id)
    {
        $this->badge_id = $badge_id;

        $jasonHashes = [
            'e4bdd8c4b3c32e5dd54d0d3934f3576fcbfd5baa',
            '778e7945ea9a8ea3d509ed12e9cce2d8ccab0a8d',
        ];

        $curses = CurseLog::whereIn('reciever_hash', $jasonHashes)
            ->where('type', 'curse')
            ->where('timestamp', '>=', $this->two_days_ago)
            ->get();

        foreach($curses as $curse)
        {
            $haveAlreadyTrusted = ProfileBadge::where('player_hash', $curse->player_hash)
                ->where('badge_id', 12)
                ->first();

            if(!$haveAlreadyTrusted)
            {
                ProfileBadge::updateOrCreate(
                    [
                        'player_hash' => $curse->player_hash,
                        'badge_id' => $this->badge_id,
                    ], 
                    [
                        'achieved_at' => $curse->timestamp
                    ]
                );

                Log::channel('sync')->info($curse->player_hash." got badge ".$badge_id);
            }

            $this->line("Found a Jason curser");
        }
    }

    public function trustJasonBadge($badge_id)
    {
        $this->badge_id = $badge_id;

        $jasonHashes = [
            'e4bdd8c4b3c32e5dd54d0d3934f3576fcbfd5baa',
            '778e7945ea9a8ea3d509ed12e9cce2d8ccab0a8d',
        ];

        $trusts = CurseLog::whereIn('reciever_hash', $jasonHashes)
            ->where('type', 'trust')
            ->where('timestamp', '>=', $this->two_days_ago)
            ->get();

        foreach($trusts as $trust)
        {
            $haveAlreadyCursed = ProfileBadge::where('player_hash', $trust->player_hash)
                ->where('badge_id', 4)
                ->first();

            if(!$haveAlreadyCursed)
            {
                ProfileBadge::updateOrCreate(
                    [
                        'player_hash' => $trust->player_hash,
                        'badge_id' => $this->badge_id,
                    ], 
                    [
                        'achieved_at' => $trust->timestamp
                    ]
                );

                Log::channel('sync')->info($trust->player_hash." got badge ".$badge_id);
            }

            $this->line("Found a Jason truster");
        }
    }
}
