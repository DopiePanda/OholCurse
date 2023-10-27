<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\LogScraper\MapLogScraper;

class HandleMapLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:handle-map-logs {offset?}';

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
        $offset = $this->argument('offset');

        $controller = new MapLogScraper();
        $controller->scrapeLog($offset);
    }
}
