<?php

use App\Http\Controllers\Api\V1\PrayerTimeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Prayer times - format to match Header.tsx expected format
Route::get('/solat', function (Request $request) {
    $controller = new PrayerTimeController();
    $result = $controller->today($request);

    // PrayerTimeResource returns a JsonResource, need to convert to response
    if (method_exists($result, 'toResponse')) {
        $response = $result->toResponse($request);
        if ($response->status() === 200) {
            // JsonResource wraps in {"data": {...}}, so unwrap the first level
            $content = json_decode($response->getContent(), true);
            return response()->json($content); // Already wrapped by PrayerTimeResource
        }
        return $response;
    }

    // JsonResponse returned on error
    return $result;
});

// Visitor count
Route::get('/visitor-count', function () {
    return response()->json([
        'dailyCount' => rand(50, 200),
        'monthlyCount' => rand(1000, 5000),
        'overallCount' => rand(10000, 50000)
    ]);
});

// Admin Panel
Route::view('/admin', 'admin');
Route::get('/{any?}', function () {
    return view('layouts.app');
})->where('any', '(?!api/|admin|storage|images).*');
