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

// Main Route
Route::get('/', function () {
    return view('welcome');
});
Route::get('/utama', function () {
    return view('index.index');
});

// Info Korporat Tab Menu
Route::get('/info-korporat/perutusan-imam-besar', function () {
    return view('corporate.perutusanImamBesar');
});
Route::get('/info-korporat/sejarah-masjid', function () {
    return view('corporate.sejarahMasjid');
});
Route::get('/info-korporat/profil-korporat', function () {
    return view('corporate.profilKorporat');
});
Route::get('/info-korporat/logo', function () {
    return view('corporate.logo');
});
Route::get('/info-korporat/carta-organisasi', function () {
    return view('corporate.cartaOrganisasi');
});
Route::get('/info-korporat/direktori-kakitangan', function () {
    return view('corporate.direktoriKakitangan');
});

// Informasi Tab Menu
Route::get('/informasi/berita-semasa', function () {
    return view('information.beritaSemasa');
});
Route::get('/informasi/kemudahan', function () {
    return view('information.kemudahan');
});
Route::get('/informasi/takwim', function () {
    return view('information.takwim');
});
Route::get('/informasi/kutipan-tabung-masjid', function () {
    return view('information.kutipanTabungMasjid');
});

// Muat Turun Tab Menu
Route::get('/muat-turun/jadual-kuliah', function () {
    return view('download.jadualKuliah');
});
Route::get('/muat-turun/nota-kuliah', function () {
    return view('download.notaKuliah');
});
Route::get('/muat-turun/borang', function () {
    return view('download.borang');
});

// Hubungi Kami Tab Menu
Route::get('/hubungi-kami', function () {
    return view('contact');
});