<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\LogScraper\LifeLogScraper;

class HandleLifeLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:handle-life-logs';

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
        $controller = new LifeLogScraper();
        $controller->scrapeLog();
    }
}
