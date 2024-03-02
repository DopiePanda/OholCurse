<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpClient\HttpClient;
use Goutte\Client;

use App\Models\CurseLog;
use App\Models\Leaderboard;

class CurseChecker extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.curse-checker';

    public $hash;

    private $base_url = 'https://onehouronelife.com/fitnessServer/server.php?action=show_leaderboard';

    public $results;

    public function mount()
    {
        $this->results = collect([]);
    }

    public function search()
    {
        $this->validate([
            'hash' => 'required|string|exists:leaderboards,player_hash',
        ]);

        $forgives = CurseLog::select('player_hash')
            ->where('reciever_hash', $this->hash)
            ->where('type', 'forgive')
            ->orderBy('id', 'desc')
            ->groupBy('player_hash')
            ->get();

        $hashes = $forgives->pluck('player_hash');

        $curses = CurseLog::with('leaderboard_recieved')
            ->whereDoesntHave('forgives', function (Builder $query) use ($hashes) {
                $query->whereIn('player_hash', $hashes);
            })
            ->select('player_hash', 'reciever_hash')
            ->where('reciever_hash', $this->hash)
            ->where('type', 'curse')
            ->groupBy('player_hash')
            ->get();

        $leaderboard_names = $curses->pluck('leaderboard_recieved.leaderboard_name')->toArray();

        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));
        $website = $client->request('GET', $this->base_url);
        $table = $website->filter('table')->eq(1);

        $rows = $table->filter('tr > td')->each(function($td, $i) {
            return $td->text();
        });

        array_shift($rows);
        array_shift($rows);

        $players = collect(array_chunk($rows, 3));
        $names = [];

        foreach ($players as $player) 
        {
            $names[] = $player[1];
        }

        //dd($players);

        $matches = array_intersect($names, $leaderboard_names);
        
        $leaderboard_ids = Leaderboard::whereIn('leaderboard_name', $matches)
            ->select('leaderboard_id')
            ->get()
            ->pluck('leaderboard_id');


        foreach($leaderboard_ids as $id)
        {
            $connection = Http::timeout(300)
                ->retry(3, 180)
                ->get('https://yum.selb.io/yumdb/api/v1/leaderboards/'.$id);

            $profile = $connection->json();
            $found = false;

            //dd($profile);

            foreach($profile['entries'] as $life)
            {
                if($found == false)
                {
                    if($life["rel"] == "You")
                    {
                        $life_end = ($life['death_min'] + $life['death_max']) / 2;

                        $this->results->push([
                            'id' => $profile['leaderboard_id'],
                            'name' => $profile['leaderboard_name'],
                            'hash' => $profile['ehash'],
                            'last_crawled' => $profile['last_crawled'],
                            'life_name' => $life['name'],
                            'life_age' => $life['age'],
                            'life_end' => $life_end,
                        ]);
                        $found = true;
                    }
                }
                //dd($this->results);
            }
        }

        //dd($this->results);
    }
}
