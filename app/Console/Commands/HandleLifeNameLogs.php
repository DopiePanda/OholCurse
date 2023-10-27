<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\LogScraper\LifeNameLogScraper;

class HandleLifeNameLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:handle-life-name-logs';

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
        $controller = new LifeNameLogScraper();
        $controller->scrapeLog();
    }
}
