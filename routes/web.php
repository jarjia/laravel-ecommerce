<?php

use App\Http\Controllers\ScrapeController;
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
})->name('home');

Route::controller(ScrapeController::class)->group(function () {
    Route::get('/table/{group}', 'dashboard')->name('table');
    Route::get('/test', 'test')->name('test');
});
