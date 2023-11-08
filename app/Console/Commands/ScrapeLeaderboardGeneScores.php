<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpClient\HttpClient;
use Goutte\Client;
use Log;

use App\Models\Leaderboard;
use App\Models\PlayerScore;

class ScrapeLeaderboardGeneScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scrape-leaderboard-gene-scores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $base_url = 'https://onehouronelife.com/fitnessServer/server.php?action=show_leaderboard';
    private $client;


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = microtime(true);

        $this->client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));
        $website = $this->client->request('GET', $this->base_url);
        $table = $website->filter('table')->eq(1);

        $rows = $table->filter('tr > td')->each(function($td, $i) {
            return $td->text();
        });

        array_shift($rows);
        array_shift($rows);

        $players = array_chunk($rows, 3);

        $count = 0;

        try{

            // Begin database transaction
            DB::beginTransaction();

            foreach($players as $player)
            {
                $profile = Leaderboard::where('leaderboard_name', $player[1])->first();

                if($profile)
                {
                    PlayerScore::updateOrCreate(
                        [
                            'leaderboard_id' => $profile->leaderboard_id,
                        ],
                        [
                            'gene_score' => $player[2],
                        ]
                    );

                    $count++;
                }
            }

            // Commit the DB transaction
            DB::commit();

            $end = microtime(true);
            $time = round(($end - $start), 3);
            
            Log::channel('sync')->info("Successfully updated $count gene scores in $time seconds");
            $this->info("Successfully updated $count gene scores in $time seconds");

        } catch(\Exception $e) {
                
            // Rollback DB transaction
            DB::rollback();

            // Log exception message
            Log::channel('sync')->error('Exception returned when updating player curse score: ');
            Log::channel('sync')->error($e);

            $this->info("Error while updating curse scores");
        }
    }
}
