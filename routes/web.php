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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/utama', function () {
    return view('index.index');
});
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

