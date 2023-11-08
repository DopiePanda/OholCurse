<?php

namespace App\Http\Controllers\LogScraper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpClient\HttpClient;
use Goutte\Client;
use Log;

use App\Models\Leaderboard;

class GeneScoreScraper extends Controller
{
    private $base_url = 'https://onehouronelife.com/fitnessServer/server.php?action=leaderboard_detail&id=';
    private $client;

    public function __construct()
    {
        $this->client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));
    }

    public function test()
    {
        $start = microtime(true);

        $leaderboards = Leaderboard::where('leaderboard_id', '>', '0')
            ->where('leaderboard_id', '<=', '100')
            ->get();

        foreach ($leaderboards as $player) 
        {
            $url = $this->getFullUrl($player->leaderboard_id);
            $score = $this->scrapeGeneScore($url);

            $player->gene_score = $score;
            $player->save();
        }

        $end = microtime(true);
        $time = round(($end - $start), 3);
        
        Log::channel('sync')->info('GENE SCORE scraper finished in: '.$time.' seconds');
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
