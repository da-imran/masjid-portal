<?php

use App\Http\Controllers\Api\V1\BeritaSemasaController;
use App\Http\Controllers\Api\V1\CorporateInfoController;
use App\Http\Controllers\Api\V1\KutipanMasjidController;
use App\Http\Controllers\Api\V1\PengumumanController;
use App\Http\Controllers\Api\V1\PrayerTimeController;
use App\Http\Controllers\Api\V1\SystemController;
use App\Http\Controllers\Api\V1\VisitorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*
|--------------------------------------------------------------------------
| Public System Routes (No authentication required)
|--------------------------------------------------------------------------
*/
Route::get('/maintenance', [SystemController::class, 'maintenanceStatus']);

// Sanctum authenticated user route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| API v1 Routes
|--------------------------------------------------------------------------
|
| Version 1 of the API endpoints. All routes are prefixed with /api/v1
|
*/

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Berita Semasa (News) Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('berita')->group(function () {
        Route::get('/', [BeritaSemasaController::class, 'index']);
        Route::get('/featured', [BeritaSemasaController::class, 'featured']);
        Route::get('/{id}', [BeritaSemasaController::class, 'show'])->where('id', '[0-9]+');
        Route::post('/{id}/view', [BeritaSemasaController::class, 'incrementView'])->where('id', '[0-9]+');
    });

    /*
    |--------------------------------------------------------------------------
    | Pengumuman (Announcements) Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('pengumuman')->group(function () {
        Route::get('/', [PengumumanController::class, 'index']);
        Route::get('/high-priority', [PengumumanController::class, 'highPriority']);
        Route::get('/{id}', [PengumumanController::class, 'show'])->where('id', '[0-9]+');
    });

    /*
    |--------------------------------------------------------------------------
    | Kutipan Masjid (Donation Collections) Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('kutipan')->group(function () {
        Route::get('/', [KutipanMasjidController::class, 'index']);
        Route::get('/summary', [KutipanMasjidController::class, 'summary']);
        Route::get('/current-month', [KutipanMasjidController::class, 'currentMonth']);
        Route::get('/{id}', [KutipanMasjidController::class, 'show'])->where('id', '[0-9]+');
    });

    /*
    |--------------------------------------------------------------------------
    | Prayer Times Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('prayer-times')->group(function () {
        Route::get('/today', [PrayerTimeController::class, 'today']);
        Route::get('/month', [PrayerTimeController::class, 'month']);
    });

    /*
    |--------------------------------------------------------------------------
    | Corporate Information Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('corporate')->group(function () {
        Route::get('/', [CorporateInfoController::class, 'index']);
        Route::get('/{slug}', [CorporateInfoController::class, 'show']);
    });

    /*
    |--------------------------------------------------------------------------
    | Visitors Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('visitors')->group(function () {
        Route::post('/track', [VisitorController::class, 'track']);
        Route::get('/stats', [VisitorController::class, 'stats']);
    });
});
