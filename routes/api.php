<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\Admin\BrowserLogController;
use App\Http\Controllers\Api\V1\Admin\PermissionController;
use App\Http\Controllers\Api\V1\Admin\RoleController;
use App\Http\Controllers\Api\V1\Admin\UserController;
use App\Http\Controllers\Api\V1\BeritaSemasaController;
use App\Http\Controllers\Api\V1\CorporateInfoController;
use App\Http\Controllers\Api\V1\DownloadController;
use App\Http\Controllers\Api\V1\KemudahanController;
use App\Http\Controllers\Api\V1\KutipanMasjidController;
use App\Http\Controllers\Api\V1\PengumumanController;
use App\Http\Controllers\Api\V1\PrayerTimeController;
use App\Http\Controllers\Api\V1\SystemController;
use App\Http\Controllers\Api\V1\TakwimController;
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
| Public Login/Logout Route
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Public System Routes (No authentication required)
|--------------------------------------------------------------------------
*/
Route::get('/maintenance', [SystemController::class, 'maintenanceStatus']);
Route::get('/v1/health', [SystemController::class, 'health']);

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
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/user-refresh', [AuthController::class, 'refresh']);
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Authentication Routes (Authenticated)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            /*
            |--------------------------------------------------------------------------
            | Browser Logging Routes
            |--------------------------------------------------------------------------
            */
            Route::prefix('logs')->group(function () {
                Route::post('/browser', [BrowserLogController::class, 'store']);
                Route::delete('/browser', [BrowserLogController::class, 'clear'])->middleware('role:admin');
            });

            /*
            |--------------------------------------------------------------------------
            | Admin User Management Routes (Permission-based access)
            |--------------------------------------------------------------------------
            */
            // Permission-based middleware applied in controllers
            Route::apiResource('users', UserController::class);
            Route::post('users/{id}/block', [UserController::class, 'block'])->where('id', '[0-9]+');
            Route::post('users/{id}/unblock', [UserController::class, 'unblock'])->where('id', '[0-9]+');
            Route::post('users/{id}/disable', [UserController::class, 'disable'])->where('id', '[0-9]+');
            Route::post('users/{id}/enable', [UserController::class, 'enable'])->where('id', '[0-9]+');
            Route::apiResource('roles', RoleController::class);
            Route::apiResource('permissions', PermissionController::class);

            // Role Permission Management
            Route::prefix('roles/{roleId}')->group(function () {
                Route::post('permissions', [RoleController::class, 'addPermission'])->where('roleId', '[0-9]+');
                Route::match(['put', 'patch'], 'permissions/{permissionId}', [RoleController::class, 'updatePermission'])
                    ->where('roleId', '[0-9]+')
                    ->where('permissionId', '[0-9]+');
                Route::delete('permissions/{permissionId}', [RoleController::class, 'removePermission'])
                    ->where('roleId', '[0-9]+')
                    ->where('permissionId', '[0-9]+');
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
        
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', [BeritaSemasaController::class, 'store'])->middleware('permission:berita.create');
            Route::match(['put', 'patch'], '/{id}', [BeritaSemasaController::class, 'update'])->where('id', '[0-9]+')->middleware('permission:berita.edit');
            Route::delete('/{id}', [BeritaSemasaController::class, 'destroy'])->where('id', '[0-9]+')->middleware('permission:berita.delete');
        });
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

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', [PengumumanController::class, 'store'])->middleware('permission:pengumuman.create');
            Route::match(['put', 'patch'], '/{id}', [PengumumanController::class, 'update'])->where('id', '[0-9]+')->middleware('permission:pengumuman.edit');
            Route::delete('/{id}', [PengumumanController::class, 'destroy'])->where('id', '[0-9]+')->middleware('permission:pengumuman.delete');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Kemudahan (Facilities) Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('kemudahan')->group(function () {
        Route::get('/', [KemudahanController::class, 'index']);
        Route::get('/{id}', [KemudahanController::class, 'show'])->where('id', '[0-9]+');
       
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', [KemudahanController::class, 'store'])->middleware('permission:kemudahan.create');
            Route::match(['put', 'patch'], '/{id}', [KemudahanController::class, 'update'])->where('id', '[0-9]+')->middleware('permission:kemudahan.edit');
            Route::delete('/{id}', [KemudahanController::class, 'destroy'])->where('id', '[0-9]+')->middleware('permission:kemudahan.delete');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Takwim (Calendar/Events) Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('takwim')->group(function () {
        Route::get('/', [TakwimController::class, 'index']);
        Route::get('/upcoming', [TakwimController::class, 'upcoming']);
        Route::get('/{id}', [TakwimController::class, 'show'])->where('id', '[0-9]+');
        
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', [TakwimController::class, 'store'])->middleware('permission:takwim.create');
            Route::match(['put', 'patch'], '/{id}', [TakwimController::class, 'update'])->where('id', '[0-9]+')->middleware('permission:takwim.edit');
            Route::delete('/{id}', [TakwimController::class, 'destroy'])->where('id', '[0-9]+')->middleware('permission:takwim.delete');
        });
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
        Route::get('/summaries', [KutipanMasjidController::class, 'summaries']);
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
