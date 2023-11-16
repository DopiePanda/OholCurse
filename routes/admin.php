<?php 
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use Illuminate\Http\Request;

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Objects\GameObjectImporter;

use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\CharacterNameController;

use App\Http\Controllers\LogScraper\LifeLogScraper;
use App\Http\Controllers\LogScraper\LifeNameLogScraper;
use App\Http\Controllers\LogScraper\GeneScoreScraper;
use App\Http\Controllers\LogScraper\CurseLogScraper;
use App\Http\Controllers\LogScraper\MapLogScraper;

use App\Livewire\Admin\Leaderboards\Index as LeaderboardsIndex;

use App\Livewire\Admin\Logs\Map\Index as MapIndex;
use App\Livewire\Admin\Logs\Map\Area as MapArea;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

Route::middleware(['web', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::prefix('/leaderboards')->name('leaderboards.')->group(function () {
        Route::get('/', LeaderboardsIndex::class)->name('index');
        Route::get('/multi/{slug?}', [LeaderboardController::class, 'getMultipleObjectsLeaderboard'])->name('multi');
    });

    Route::prefix('/search')->name('search.')->group(function () {
        Route::get('/map', MapIndex::class)->name('map.index');
        Route::get('/map/area', MapArea::class)->name('map.area');
    });

    Route::prefix('/import')->name('import.')->group(function () {
        Route::get('/objects', [GameObjectImporter::class, 'parseObjectsFile'])->name('objects');
        Route::get('/names', [CharacterNameController::class, 'importNames'])->name('names');
    });

    Route::prefix('scrape')->name('scrape.')->group(function () {
        Route::get('/maplog/{offset?}', [MapLogScraper::class, 'scrapeLog'])->name('maplog');
    });

    Route::prefix('session')->name('session.')->group(function () {
        Route::get('/menu/hide', [SessionController::class, 'hideMenu'])->name('menu.hide');
        Route::get('/menu/show', [SessionController::class, 'showMenu'])->name('menu.show');
    });

    Route::get('/ghost/{character_id}', function ($id) 
    {
        $birth = \App\Models\LifeLog::where('character_id', $id)->where('type', 'birth')->first();
        $death = \App\Models\LifeLog::where('character_id', $id)->where('type', 'death')->first();

        if($birth)
        {
            if($death)
            {
                $time_alive = $death->timestamp - $birth->timestamp;
                if($time_alive <= 3600)
                {
                    print 'Player was not a ghost';
                }else
                {
                    print 'Player WAS a ghost';
                }
            }else
            {
                print 'Player death not yet recorded';
            }
        }else
        {
            print 'Player birth not yet recorded';
        }
    });
 
    Route::get('/user/profile', function () {
        // Uses first & second middleware...
    });
});