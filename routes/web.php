<?php

use App\Http\Controllers\Web\IndexController;
use App\Http\Controllers\Web\BeritaController;
use App\Http\Controllers\Web\PengumumanController;
use App\Http\Controllers\Web\KutipanController;
use App\Http\Controllers\Web\CorporateController;
use App\Http\Controllers\Web\DownloadController;
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

// Home Page (using existing index view)
Route::get('/', [IndexController::class, 'index'])->name('home');

// Information Routes
Route::prefix('info')->group(function () {
    Route::get('/berita-semasa', [BeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita-semasa/{id}', [BeritaController::class, 'show'])->name('berita.show');
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::get('/pengumuman/{id}', [PengumumanController::class, 'show'])->name('pengumuman.show');
    Route::get('/kutipan-tabung-masjid', [KutipanController::class, 'index'])->name('kutipan.index');
    Route::get('/kemudahan', function () { return view('information.kemudahan'); })->name('kemudahan');
    Route::get('/takwim', function () { return view('information.takwim'); })->name('takwim');
});

// Corporate Routes
Route::prefix('corporate')->group(function () {
    Route::get('/profil-korporat', [CorporateController::class, 'profil'])->name('corporate.profil');
    Route::get('/sejarah-masjid', [CorporateController::class, 'sejarah'])->name('corporate.sejarah');
    Route::get('/carta-organisasi', [CorporateController::class, 'carta'])->name('corporate.carta');
    Route::get('/direktori-kakitangan', [CorporateController::class, 'direktori'])->name('corporate.direktori');
    Route::get('/perutusan-imam-besar', [CorporateController::class, 'perutusan'])->name('corporate.perutusan');
    Route::get('/logo', [CorporateController::class, 'logo'])->name('corporate.logo');
});

// Download Routes
Route::prefix('download')->group(function () {
    Route::get('/jadual-kuliah', [DownloadController::class, 'jadual'])->name('download.jadual');
    Route::get('/nota-kuliah', [DownloadController::class, 'nota'])->name('download.nota');
    Route::get('/borang', [DownloadController::class, 'borang'])->name('download.borang');
});

// Contact
Route::get('/hubungi', function () {
    return view('contact');
})->name('contact');

// Fallback
Route::fallback(function () {
    return redirect('/');
});
