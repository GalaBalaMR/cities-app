<?php

use App\Http\Controllers\CityController;
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

Route::get('/', [CityController::class, 'index'])->name('home');
Route::get('/city/{id}', [CityController::class, 'show'])->name('show');
Route::get('/search', [CityController::class, 'search'])->name('search');
