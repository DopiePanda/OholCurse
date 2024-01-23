<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

use App\Http\Controllers\CurseLogController;
use App\Http\Controllers\CurseScraperController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\LeaderboardScraperController;
use App\Http\Controllers\LifeScraperController;
use App\Http\Controllers\LifeDataController;
use App\Http\Controllers\MapLeaderboardController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlayerReportController;
use App\Http\Controllers\ReportVerificationController;
use App\Http\Controllers\TestController;

use App\Http\Controllers\Tools\Select2Controller;

use App\Livewire\Dashboard;
use App\Livewire\Home;
use App\Livewire\CharacterNames;
use App\Livewire\Statistics;
use App\Livewire\PlayerInteractions;
use App\Livewire\Map\Leaderboard;
use App\Livewire\Modals\Charts\LeaderboardRecords;

use App\Livewire\Roadmap\Ideas;
use App\Livewire\Roadmap\Ideas\Create as IdeaCreate;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware('web')->group(function() {

    Route::get('/sitemap/generate', function(){
        SitemapGenerator::create('https://oholcurse.com')->getSitemap()->writeToDisk('public', 'sitemap.xml');
    });
    
    Route::get('/', Home::class)->name('search');
    Route::get('/player/hash/{hash}', [PlayerReportController::class, 'fetch'])->name('player.curses');
    Route::get('/player2', PlayerInteractions::class)->name('player.interactions');
    Route::get('/player/lives/{hash}', [PlayerReportController::class, 'lives'])->name('player.lives');
    Route::get('/player/reports/{hash}', [PlayerReportController::class, 'reports'])->name('player.reports');
    Route::get('/player/records/{hash}', [PlayerReportController::class, 'records'])->name('player.records');
    
    Route::get('/names', CharacterNames::class)->name('names');
    Route::get('/statistics', Statistics::class)->name('statistics');
    Route::get('/chart', LeaderboardRecords::class)->name('statistics');

    Route::prefix('/roadmap')->name('roadmap.')->group(function () {
        Route::get('/', Ideas::class)->name('index');
    });

    Route::prefix('/leaderboards')->name('leaderboards.')->group(function () {
        Route::get('/', [LeaderboardController::class, 'index'])->name('index');
        Route::get('/daily', Leaderboard::class)->name('daily');
        Route::get('/weekly', [LeaderboardController::class, 'weekly'])->name('weekly');
        Route::get('/weekly/object/{object_id}', [LeaderboardController::class, 'getObjectLeaderboard'])->name('weekly.single');
        Route::get('/weekly/multi/{id}', [LeaderboardController::class, 'getMultiObjectsLeaderboard'])->name('weekly.multi');
        Route::get('/all-time', [LeaderboardController::class, 'allTime'])->name('all-time');
        Route::get('/all-time/ghost', [LeaderboardController::class, 'allTimeGhost'])->name('all-time-ghost');

    });

    Route::get('/families/index', [FamilyController::class, 'index'])->name('families.index');
    Route::get('/families/view/{character_id}', [FamilyController::class, 'view'])->name('families.view');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::patch('/profile/timezone', [ProfileController::class, 'updateTimezone'])->name('timezone.update');
        Route::patch('/profile/theme', [ProfileController::class, 'updateTheme'])->name('theme.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        Route::get('/verify-reports/{id}', [ReportVerificationController::class, 'verifyAllByUser']);

        Route::resource('guides', GuideController::class);
        Route::get('/guides/view/{slug}', [GuideController::class, 'show'])->name('guides.view');
        Route::post('upload', [GuideController::class, 'upload'])->name('upload');

        Route::get('/roadmap/ideas/create', IdeaCreate::class)->name('roadmap.idea.create');

        Route::get('/phpinfo', function () {
            return redirect()->route('search');
        });

        Route::get('/select2/ajax', [Select2Controller::class, 'handle'])->name('select2.ajax');
        //Route::get('/interactions/{object_id}/{ghost?}', [TestController::class, 'getObjectInteractions'])->name('interactions');
    });

    

});

require __DIR__.'/auth.php';
