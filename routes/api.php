<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
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

Route::group(['controller' => MailController::class], function () {
    Route::post('/send', 'send')->name('send.mail');
});

Route::group(['controller' => AuthController::class], function () {
    Route::post('/register', 'store')->name('user.create');
    Route::post('/login', 'login')->name('auth.login');
    Route::post('/verify', 'verify')->name('auth.verify');
    Route::post('/send-verify', 'sendVerify')->name('auth.send.verify');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['controller' => AuthController::class], function () {
        Route::get('/user', 'user')->name('auth.data');
        Route::get('/logout', 'logout')->name('logout.user');
    });

    Route::group(['controller' => CartController::class], function () {
        Route::post('/cart', 'create')->name('create.cart.item');
        Route::get('/cart', 'index')->name('get.cart.items');
        Route::get('/cart/count', 'indexCount')->name('get.cart.count');
        Route::delete('/cart/{cart}', 'destroy')->name('destroy.cart.item');
        Route::post('/cart/clear', 'destroyAll')->name('destroy.cart.items');
        Route::post('/cart/checkout', 'checkoutProducts')->name('checkout.cart.items');
        Route::put('/cart/quantity/{cart}', 'changeQuantity')->name('change.quantity.cart.items');
    });

    Route::group(['controller' => OrderController::class], function () {
        Route::get('/orders', 'index')->name('get.orders');
        Route::delete('/orders/{order}', 'destroy')->name('destroy.orders');
    });

    Route::group(['controller' => CheckoutController::class], function () {
        Route::post('/checkout', 'checkout')->name('checkout');
    });

    Route::group(['controller' => ProductController::class], function () {
        Route::post('/product', 'create')->name('product.create');
        Route::get('/products', 'index')->name('get.products');
        Route::get('/products/user', 'indexUser')->name('get.user.products');
        Route::get('/products/{productId}', 'show')->name('get.product');
        Route::patch('/products/{productId}', 'update')->name('edit.product');
        Route::delete('/products/{productId}', 'destroy')->name('destroy.product');
    });
});
