<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Log;
use DB;

use App\Models\CurseLog;
use App\Models\PlayerScore;

class UpdateCurseScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-curse-scores';

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

        $start = Carbon::now('UTC')->subDays(2);
        $start = $start->setTimeFromTimeString('08:30:00');

        $end = Carbon::now('UTC');
        $end = $end->setTimeFromTimeString('08:30:00');
        
        $scores = CurseLog::with('leaderboard_recieved')
                    ->where('type', 'score')
                    ->where('curse_score', '>', 0)
                    ->where('timestamp', '<=', $end->timestamp)
                    ->where('timestamp', '>=', $start->timestamp)
                    ->orderBy('curse_score', 'asc')
                    ->get();

        $start = microtime(true);
        $bar = $this->output->createProgressBar(count($scores));
        $bar->start();

        $count = 0;

        try{

            // Begin database transaction
            DB::beginTransaction();

            foreach($scores as $score)
            {
                if($score->leaderboard_recieved != null)
                {
                    PlayerScore::updateOrCreate(
                        [
                            'leaderboard_id' => $score->leaderboard_recieved->leaderboard_id,
                        ],
                        [
                            'curse_score' => $score->curse_score,
                        ]
                    );
                    
                    $count++;
                }
                $bar->advance();
            }

            // Commit the DB transaction
            DB::commit();

            $bar->finish();
            $end = microtime(true);
            $time = round(($end - $start), 3);

            Log::info("Successfully updated $count curse scores in $time seconds");
            $this->info("Successfully updated $count curse scores in $time seconds");

        } catch(\Exception $e) {
                
            // Rollback DB transaction
            DB::rollback();

            // Log exception message
            Log::error('Exception returned when updating player curse score: ');
            Log::error($e);

            $this->info("Error while updating curse scores");
        }
    }
}
