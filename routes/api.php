<?php

use App\Http\Controllers\Api\V1\Admin\AuthController;
use App\Http\Controllers\Api\V1\Admin\BeritaSemasaController as AdminBeritaSemasaController;
use App\Http\Controllers\Api\V1\Admin\KemudahanController;
use App\Http\Controllers\Api\V1\Admin\RoleController;
use App\Http\Controllers\Api\V1\Admin\TakwimController;
use App\Http\Controllers\Api\V1\Admin\UserController;
use App\Http\Controllers\Api\V1\BeritaSemasaController;
use App\Http\Controllers\Api\V1\CorporateInfoController;
use App\Http\Controllers\Api\V1\DownloadController;
use App\Http\Controllers\Api\V1\KemudahanController as PublicKemudahanController;
use App\Http\Controllers\Api\V1\KutipanMasjidController;
use App\Http\Controllers\Api\V1\PengumumanController;
use App\Http\Controllers\Api\V1\PrayerTimeController;
use App\Http\Controllers\Api\V1\SystemController;
use App\Http\Controllers\Api\V1\TakwimController as PublicTakwimController;
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
    | Admin Authentication Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/refresh', [AuthController::class, 'refresh']);

            /*
            |--------------------------------------------------------------------------
            | Admin User Management Routes (Admin only)
            |--------------------------------------------------------------------------
            */
            Route::middleware('role:admin')->group(function () {
                Route::apiResource('users', UserController::class);
                Route::post('users/{id}/block', [UserController::class, 'block'])->where('id', '[0-9]+');
                Route::post('users/{id}/unblock', [UserController::class, 'unblock'])->where('id', '[0-9]+');
                Route::apiResource('roles', RoleController::class);
            });

            /*
            |--------------------------------------------------------------------------
            | Admin Berita Semasa Routes
            |--------------------------------------------------------------------------
            */
            Route::prefix('berita')->group(function () {
                Route::get('/', [AdminBeritaSemasaController::class, 'index'])->middleware('permission:berita.view');
                Route::get('/{id}', [AdminBeritaSemasaController::class, 'show'])->where('id', '[0-9]+')->middleware('permission:berita.view');
                Route::post('/', [AdminBeritaSemasaController::class, 'store'])->middleware('permission:berita.create');
                Route::match(['put', 'patch'], '/{id}', [AdminBeritaSemasaController::class, 'update'])->where('id', '[0-9]+')->middleware('permission:berita.edit');
                Route::delete('/{id}', [AdminBeritaSemasaController::class, 'destroy'])->where('id', '[0-9]+')->middleware('permission:berita.delete');
            });

            /*
            |--------------------------------------------------------------------------
            | Admin Kemudahan Routes
            |--------------------------------------------------------------------------
            */
            Route::prefix('kemudahan')->group(function () {
                Route::get('/', [KemudahanController::class, 'index'])->middleware('permission:kemudahan.view');
                Route::get('/{id}', [KemudahanController::class, 'show'])->where('id', '[0-9]+')->middleware('permission:kemudahan.view');
                Route::post('/', [KemudahanController::class, 'store'])->middleware('permission:kemudahan.create');
                Route::match(['put', 'patch'], '/{id}', [KemudahanController::class, 'update'])->where('id', '[0-9]+')->middleware('permission:kemudahan.edit');
                Route::delete('/{id}', [KemudahanController::class, 'destroy'])->where('id', '[0-9]+')->middleware('permission:kemudahan.delete');
            });

            /*
            |--------------------------------------------------------------------------
            | Admin Takwim Routes
            |--------------------------------------------------------------------------
            */
            Route::prefix('takwim')->group(function () {
                Route::get('/', [TakwimController::class, 'index'])->middleware('permission:takwim.view');
                Route::get('/{id}', [TakwimController::class, 'show'])->where('id', '[0-9]+')->middleware('permission:takwim.view');
                Route::post('/', [TakwimController::class, 'store'])->middleware('permission:takwim.create');
                Route::match(['put', 'patch'], '/{id}', [TakwimController::class, 'update'])->where('id', '[0-9]+')->middleware('permission:takwim.edit');
                Route::delete('/{id}', [TakwimController::class, 'destroy'])->where('id', '[0-9]+')->middleware('permission:takwim.delete');
            });
        });
    });

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
    | Kemudahan (Facilities) Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('kemudahan')->group(function () {
        Route::get('/', [PublicKemudahanController::class, 'index']);
        Route::get('/{id}', [PublicKemudahanController::class, 'show'])->where('id', '[0-9]+');
    });

    /*
    |--------------------------------------------------------------------------
    | Takwim (Calendar/Events) Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('takwim')->group(function () {
        Route::get('/', [PublicTakwimController::class, 'index']);
        Route::get('/upcoming', [PublicTakwimController::class, 'upcoming']);
        Route::get('/{id}', [PublicTakwimController::class, 'show'])->where('id', '[0-9]+');
    });

    /*
    |--------------------------------------------------------------------------
    | Downloads Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('downloads')->group(function () {
        Route::get('/{category}', [DownloadController::class, 'index'])->where('category', '[a-z_]+');
        Route::get('/item/{id}', [DownloadController::class, 'show'])->where('id', '[0-9]+');
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
