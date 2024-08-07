<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\CurseLogController;
use App\Http\Controllers\CurseScraperController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\LeaderboardScraperController;
use App\Http\Controllers\LifeScraperController;
use App\Http\Controllers\LifeDataController;
use App\Http\Controllers\MapLeaderboardController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlayerReportController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\ReportVerificationController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\YumlifeController;

use App\Http\Controllers\Tools\Select2Controller;
use App\Http\Controllers\Objects\PhexDataImporter;

use App\Livewire\Dashboard;
use App\Livewire\Home;
use App\Livewire\CharacterNames;
use App\Livewire\GameStats;
use App\Livewire\Statistics;
use App\Livewire\PlayerInteractions;
use App\Livewire\Player\Lives as PlayerLives;
use App\Livewire\Player\Statistics as PlayerStatistics;
use App\Livewire\Map\Leaderboard;
use App\Livewire\Leaderboards\AllTime as LeaderboardAllTime;
use App\Livewire\Wanted\Poster as BerryPoster;
use App\Livewire\Modals\Charts\LeaderboardRecords;
use App\Livewire\Admin\Search\CharacterMovement;
use App\Livewire\Admin\Player\BirthCheck;
use App\Livewire\Admin\Player\TwinCheck;

use App\Livewire\Content\Upload;
use App\Livewire\Content\Browse;

use App\Livewire\News\Index as NewsIndex;
use App\Livewire\News\Article as NewsArticle;
use App\Livewire\News\SubmitArticle as NewsSubmission;
use App\Livewire\News\Ads\Download as NewsDownload;

use App\Livewire\Roadmap\Ideas;
use App\Livewire\Roadmap\Ideas\Create as IdeaCreate;

use App\Livewire\Tools\MapDistanceCalculator;

use App\Models\LifeLog;
use App\Models\GrieferProfile;

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

    Route::get('/internal/session/get', [SessionController::class, 'getSession'])->name('session.get');
    Route::post('/internal/session/set', [SessionController::class, 'setSession'])->name('session.set');

    Route::get('/redirect/profile/{name}', [YumlifeController::class, 'findPlayerProfile']);

    Route::prefix('/player')->name('player.')->group(function () {
        Route::get('/hash/{hash}', PlayerInteractions::class)->name('curses');
        Route::get('/interactions/{hash}', PlayerInteractions::class)->name('interactions');
        Route::get('/lives/{hash}', PlayerLives::class)->name('lives');
        Route::get('/reports/{hash}', [PlayerReportController::class, 'reports'])->name('reports');
        Route::get('/records/{hash}', [PlayerReportController::class, 'records'])->name('records');
        Route::get('/statistics/{hash}', PlayerStatistics::class)->name('statistics');
    });

    Route::get('/names', CharacterNames::class)->name('names');
    Route::get('/statistics', Statistics::class)->name('statistics');
    Route::get('/stats', GameStats::class)->name('game-stats');
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
        Route::get('/all-time', LeaderboardAllTime::class)->name('all-time');
    });

    Route::prefix('/families')->name('families.')->group(function () {
        Route::get('/', [FamilyController::class, 'index'])->name('index');
        Route::get('/view/{character_id}', [FamilyController::class, 'view'])->name('view');
    });

    Route::prefix('/tools')->name('tools.')->group(function () {
        Route::get('/names', CharacterNames::class)->name('names');
        Route::get('/calculator/map', MapDistanceCalculator::class)->middleware('auth')->name('calculator.map');
    });

    Route::prefix('/news')->name('news.')->group(function () {
        Route::get('/', NewsIndex::class)->name('index');
        Route::get('/article/{id}-{slug}', NewsArticle::class)->name('article');

        Route::get('/ads/{id}', NewsDownload::class)->name('download');
    });

    Route::get('/wanted/berries', BerryPoster::class)->name('wanted.berries');

    Route::get('/privacy-policy', [PrivacyPolicyController::class, 'read'])->name('privacy-policy');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::patch('/profile/timezone', [ProfileController::class, 'updateTimezone'])->name('timezone.update');
        Route::patch('/profile/badges', [ProfileController::class, 'updateBadges'])->name('badges.update');
        Route::patch('/profile/background', [ProfileController::class, 'updateBackground'])->name('background.update');
        Route::patch('/profile/theme', [ProfileController::class, 'updateTheme'])->name('theme.update');
        Route::patch('/profile/darkmode', [ProfileController::class, 'updateDarkmode'])->name('darkmode.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        Route::get('/verify-reports/{id}', [ReportVerificationController::class, 'verifyAllByUser']);

        Route::resource('guides', GuideController::class);
        Route::get('/guides/view/{slug}', [GuideController::class, 'show'])->name('guides.view');
        Route::post('upload', [GuideController::class, 'upload'])->name('upload');

        Route::get('/roadmap/ideas/create', IdeaCreate::class)->name('roadmap.idea.create');

        Route::get('/news/submit', NewsSubmission::class)->name('news.submit');

        Route::prefix('/content')->name('content.')->group(function () {
            Route::get('/browse', Browse::class)->name('browse');
            Route::get('/upload', Upload::class)->name('upload');
        });

        Route::get('/search/births', BirthCheck::class);
        Route::get('/search/twins', TwinCheck::class);
        Route::get('/search/movement', CharacterMovement::class)->name('search.movement');
        Route::get('/select2/ajax', [Select2Controller::class, 'handle'])->name('select2.ajax');
        
        Route::impersonate();

        Route::get('/donations', [DonationController::class, 'get']);
    });

    

});

require __DIR__.'/auth.php';
