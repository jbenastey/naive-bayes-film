<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\KomentarController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [DashboardController::class,'index'])->middleware('auth');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

Route::resource('film', FilmController::class);
Route::resource('komentar', KomentarController::class);
Route::post('komentar/upload', [KomentarController::class,'upload'])->name('komentar.upload');
Route::post('film/submit/{id}', [FilmController::class,'submit'])->name('film.submit');
Route::get('film/perhitungan/{id}', [FilmController::class,'perhitungan'])->name('film.perhitungan');
Route::get('film/detail-perhitungan/{id}', [FilmController::class,'detailPerhitungan'])->name('film.detail-perhitungan');
