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
Route::get('/utama','App\Http\Controllers\IndexController@index');

// Info Korporat Tab Menu
Route::get('/info-korporat/{id}','App\Http\Controllers\InfoKorporatController@index');

// Informasi Tab Menu
// Module Berita Semasa & Pengumuman
Route::get('/informasi/berita-semasa','App\Http\Controllers\BeritaSemasaController@index');
Route::get('/informasi/berita-semasa/{id}', 'App\Http\Controllers\BeritaSemasaController@show')->name('berita.show');
Route::get('/informasi/pengumuman/{id}', 'App\Http\Controllers\PengumumanController@show')->name('pengumuman.show');

// Module Kemudahan
Route::get('/informasi/kemudahan','App\Http\Controllers\KemudahanController@index');

// Module Takwim
Route::get('/informasi/takwim','App\Http\Controllers\TakwimController@index');

// Module Kutipan Tabung Masjid
Route::get('/informasi/kutipan-tabung','App\Http\Controllers\KutipanMasjidController@index');

// Module Turun Tab
Route::get('/muat-turun/{id}','App\Http\Controllers\MuatTurunController@index');

// Hubungi Kami Tab Menu
Route::get('/hubungi-kami','App\Http\Controllers\HubungiController@index');