<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use Carbon\Carbon;
use ZipArchive;
use Log;

class CleanMapLogEntries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-map-log-entries';

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
        //$bigArray - an array with large data
        DB::table('map_logs')
            ->where('id', '>=', 67276268)
            ->where('id', '<=', 73941073)
            ->where('created_at', '!=', null)
            ->delete();
    }
}
