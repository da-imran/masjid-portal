<?php

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

// API Routes (for React frontend)
Route::prefix('api')->group(function () {
    // Prayer times
    Route::get('/solat', function () {
        return response()->json([
            'data' => app('App\Http\Controllers\APIController')->ApiSolat()->getData()->data ?? null
        ]);
    });

    // Visitor count
    Route::get('/visitor-count', function () {
        return response()->json([
            'dailyCount' => rand(50, 200),
            'monthlyCount' => rand(1000, 5000),
            'overallCount' => rand(10000, 50000)
        ]);
    });

    // News (Berita)
    Route::get('/berita', function () {
        $beritaList = \App\Models\BeritaSemasa::active()->published()->orderBy('published_at', 'desc')->take(10)->get();
        return response()->json($beritaList);
    });

    Route::get('/berita/{id}', function ($id) {
        $berita = \App\Models\BeritaSemasa::active()->published()->findOrFail($id);
        $berita->increment('view_count');
        return response()->json($berita);
    });

    // Announcements (Pengumuman)
    Route::get('/pengumuman', function () {
        $pengumumanList = \App\Models\Pengumuman::orderBy('created_at', 'desc')->take(10)->get();
        return response()->json($pengumumanList);
    });

    Route::get('/pengumuman/{id}', function ($id) {
        $pengumuman = \App\Models\Pengumuman::findOrFail($id);
        return response()->json($pengumuman);
    });

    // Fund Collection (Kutipan)
    Route::get('/kutipan', function () {
        $kutipanList = \App\Models\KutipanMasjid::orderBy('created_at', 'desc')->paginate(50);
        return response()->json($kutipanList->items());
    });
});

// Admin Panel
Route::view('/admin', 'admin');

// SPA Fallback - Serve the React app for all non-API routes
Route::get('/{any?}', function () {
    return view('layouts.app');
})->where('any', '.*');
