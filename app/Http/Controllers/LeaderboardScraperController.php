<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Log;

use App\Models\Leaderboard;
use App\Models\PlayerScore;

class LeaderboardScraperController extends Controller
{
    public function execute()
    {
        // Get authorization values
        $url = config('services.yumdb.leaderboard_url');

        $start = microtime(true);
        Log::channel('sync')->info('LEADERBOARD scraper started');

        // Open HTTP connection and fetch data as JSON
        $connection = Http::get($url);
        $records = $connection->json();

        Log::channel('sync')->info("Fetched ".count($records)." records from Selb's API");

        // Get total count of entries in database
        $count = Leaderboard::all();
        Log::channel('sync')->info("Current records in database: ".$count->count());

        // If the amount of fetched records are greater than or equal to the amount of stored records, run query.
        if(count($records) > $count)
        {
            // Start DB transaction
            try {
                // Disable foreign key checks for achievement progress
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');

                // Truncate table to reset ID auto-increment
                DB::table('leaderboards')->truncate();

                // Begin database transaction
                DB::beginTransaction();

                // Loop through array and insert each record
                foreach ($records as $record) 
                {
                    DB::table('leaderboards')->insert([
                        'player_hash' => $record["ehash"],
                        'leaderboard_id' => $record["leaderboard_id"],
                        'leaderboard_name' => $record["leaderboard_name"],
                    ]);

                    PlayerScore::updateOrCreate(
                        [
                            'leaderboard_id' => $record["leaderboard_id"],
                        ],
                        [
                            'player_hash' => $record["ehash"],
                        ]
                    );
                }

                // Commit the DB transaction
                DB::commit();

                // Re-enable foreign key constraints
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } catch(\Exception $e) {
                
                // Rollback DB transaction
                DB::rollback();

                // Log exception message
                Log::channel('sync')->error('Exception returned when updating leaderboard entries:');
                Log::channel('sync')->error($e);
            }
        }else{
            Log::channel('sync')->error('Fewer leaderboard entries than stored.');
        }

        $end = microtime(true);

        /*
        print "Time to fetch data: ".round(($checkpoint-$start), 3)."<br/>";
        print "Time to insert data: ".round(($end-$checkpoint), 3)."<br/>";
        print "Time total: ".round(($end-$start), 3);
        */
        $time = round(($end-$start), 3);
        $new_count = count($records);

        Log::channel('sync')->info("Updated leaderboard. $count to $new_count entries in: $time");
    }

}
