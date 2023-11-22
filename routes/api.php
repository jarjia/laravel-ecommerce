<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CountriesController;
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

Route::group(['controller' => CountriesController::class], function () {
    Route::get('/countries', 'index')->name('countries.index');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::group(['controller' => AuthController::class], function () {
        Route::get('/user', 'user')->name('auth.data');
        Route::get('/logout', 'logout')->name('logout.user');
        Route::post('/post', 'test')->name('test');
    });
});

Route::group(['controller' => AuthController::class], function () {
    Route::post('/register', 'store')->name('user.create');
    Route::post('/login', 'login')->name('auth.login');
    Route::post('/verify', 'verify')->name('auth.verify');
    Route::post('/send-verify', 'sendVerify')->name('auth.send.verify');
});
