<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Console\Commands\HandleCurseLogs;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Fetch public logs for life, life names and curses
        $schedule->command('app:handle-life-logs')->dailyAt('08:40');
        $schedule->command('app:handle-life-name-logs')->dailyAt('08:40');
        $schedule->command('app:handle-curse-logs')->dailyAt('08:40');
        
        $schedule->command('app:handle-food-logs')->dailyAt('08:50');
        
        // Fetch updated leaderboards from Selb's API
        $schedule->command('app:handle-leaderboard-logs')->dailyAt('08:50');

        // Fetch public logs for map interactions
        $schedule->command('app:process-map-log')->dailyAt('09:00');

        // Update all time map interaction leaderboard
        $schedule->command('app:update-leaderboard-records')->dailyAt('09:10');

        // Update curse and gene scores
        $schedule->command('app:update-curse-scores')->dailyAt('09:10');
        $schedule->command('app:scrape-leaderboard-gene-scores')->hourly();

        //$schedule->command('app:update-family-lineage')->dailyAt('09:20');
        
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
