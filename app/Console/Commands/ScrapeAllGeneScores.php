<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpClient\HttpClient;
use Goutte\Client;
use Log;

use App\Models\Leaderboard;

class ScrapeAllGeneScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scrape-all-gene-scores {id_start}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $base_url = 'https://onehouronelife.com/fitnessServer/server.php?action=leaderboard_detail&id=';
    private $client;
    private $limit = 2000;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));

        $start = microtime(true);

        $id_start = $this->argument('id_start');
        $id_end = $id_start + $this->limit;

        $leaderboards = Leaderboard::where('leaderboard_id', '>=', $id_start)
            ->where('leaderboard_id', '<=', $id_end)
            ->where('gene_score', 0)
            ->select('leaderboard_id')
            ->get();

        $bar = $this->output->createProgressBar(count($leaderboards));
        $bar->start();

        try{

            // Begin database transaction
            DB::beginTransaction();

            foreach ($leaderboards as $player) 
            {
                $url = $this->getFullUrl($player->leaderboard_id);
                $score = $this->scrapeGeneScore($url);

                /* $player->gene_score = $score;
                $player->save(); */

                DB::table('leaderboards')
                    ->where('leaderboard_id', $player->leaderboard_id)
                    ->update(['gene_score' => $score]);

                $bar->advance();
            }

            // Commit the DB transaction
            DB::commit();

            $bar->finish();

        }catch(\Exception $e) {
                
            // Rollback DB transaction
            DB::rollback();

            // Log exception message
            Log::channel('sync')->error('Exception returned when syncing gene scores:');
            Log::channel('sync')->error($e);
        }

        $end = microtime(true);
        $time = round(($end - $start), 3);
        
        Log::channel('sync')->info('GENE SCORE scraper finished in: '.$time.' seconds');
        $this->info("Successfully scraped gene scores for IDs: $id_start - $id_end in $time seconds");
    }

    public function getFullUrl($id)
    {
        return $this->base_url.$id;
    }

    public function scrapeGeneScore($url)
    {
        $website = $this->client->request('GET', $url);

        $score = $website->filter('table')->eq(1);

        $row = $score->filter('tr > td')->each(function($td, $i) {
            return $td;
        });

        return $row[9]->text();
    }
}
