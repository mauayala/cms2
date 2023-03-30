<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v2;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v2')->group(function(){
    Route::get('login', [v2\LoginController::class, 'background']);
    Route::post('login', [v2\LoginController::class, 'login']);

    Route::middleware('validAccessToken')->group(function(){
        Route::get('app-version', [v2\AppVersionController::class, 'index']);

        Route::get('events', [v2\EventController::class, 'index']);

        Route::get('/favorites', [v2\MainController::class, 'favorites']);

        Route::prefix('kids-zone')->group(function(){
            Route::get('/', [v2\KidsZoneController::class, 'index']);
            Route::get('/favorites', [v2\KidsZoneController::class, 'favorites']);
            Route::get('/recent-viewed', [v2\KidsZoneController::class, 'recentViewed']);
        });
        
        Route::get('/recent', [v2\MainController::class, 'recent']);
        Route::get('/recent-viewed', [v2\MainController::class, 'recentViewed']);
        Route::get('/recommendations', [v2\RecommendationController::class, 'index']);
        Route::get('/search', [v2\MainController::class, 'search']);
        
        Route::prefix('series')->group(function(){
            Route::get('/', [v2\SerieController::class, 'index']);
            Route::get('/most-watched', [v2\SerieController::class, 'mostWatched']);
        });

        Route::prefix('videos')->group(function(){
            Route::get('/', [v2\VideoController::class, 'index']);
            Route::get('/most-watched', [v2\VideoController::class, 'mostWatched']);
        });
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

