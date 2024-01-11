<?php

namespace App\Livewire\Admin\Search;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpClient\HttpClient;
use Goutte\Client;
use Log;

class Leaderboards extends Component
{
    private $base_url = 'https://yum.selb.io/yumdb/api/v1/leaderboards';
    private $client;

    public $search;
    public $live;
    public $results;

    public $request_time;

    public function mount()
    {

    }

    public function render()
    {
        return view('livewire.admin.search.leaderboards');
    }

    /**
     * Execute the console command.
     */
    public function process()
    {
        $start = microtime(true);

        $validated = $this->validate([ 
            'search' => 'required|min:3',
        ]);

        // Prepare they payload array
        $query = [
            'name' => $this->search,
            'filter_entries' => 1,
        ];

        if($this->live == true)
        {
            $query['live'] = true;
        }

        // Open HTTP connection and fetch data as JSON
        
        $connection = Http::timeout(300)
                        ->retry(3, 180)
                        ->withQueryParameters($query)
                        ->get('https://yum.selb.io/yumdb/api/v1/leaderboards');
        //dd($connection);

        $this->results = $connection->json();
        $this->request_time = microtime(true) - $start;
    }
}
