<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\OtpController;
use App\Http\Controllers\Api\V1\Auth\PasswordController;
use App\Http\Controllers\Api\V1\Chat\ChatController;
use App\Http\Controllers\Api\V1\LoginAnswer\LoginAnswerController;
use App\Http\Controllers\Api\V1\Product\ProductController;
use App\Http\Controllers\Api\V1\TrackingProduct\TrackingProductController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'controller' => AuthController::class,'middleware' => ['throttle:5,1']], function () {
    Route::group(['prefix' => 'sign'], function () {
        Route::post('in', 'signIn');
        Route::post('up', 'signUp');
        Route::post('out', 'signOut');
    });

});
Route::group(['prefix' => 'otp', 'controller' => OtpController::class,'middleware' => ['throttle:5,1']], function () {
    Route::get('/', 'send');
    Route::post('/verify', 'verify');
});
Route::get('login_answers', [LoginAnswerController::class, 'index']);

Route::group(['controller' => PasswordController::class ,'middleware' => ['throttle:5,1']], function () {
    Route::post('lost-image', 'forgetPassword');
    Route::post('lost-image-check-code', 'checkCode');
//    Route::post('reset-password','resetPassword');
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'products', 'controller' => ProductController::class], function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::post('/', [ProductController::class, 'store'])->name('products.store');
        Route::post('/upload', [ProductController::class, 'upload'])->name('products.upload');
    });
    Route::get('stock', [ProductController::class, 'stock'])->name('products.stock');
    Route::get('analytics', [ProductController::class, 'analytics'])->name('products.analytics');
    Route::get('analytics/sales-predictions',
        [ProductController::class, 'salesPredictions'])->name('products.predections');

    Route::group(['prefix' => 'chat', 'controller' => ChatController::class], function () {
        Route::get('/', 'chatMessages');
        Route::post('/', 'storeMessage');
        Route::post('/add-to-favourites/{id}', 'addToFavourites');
        Route::get('/favorites', 'favourites');
    });

    Route::group(['prefix' => 'tracking-products', 'controller' => TrackingProductController::class], function () {
        Route::get('/', 'index')->name('tracking-products.index');
        Route::post('/', 'store')->name('tracking-products.store');
        Route::get('/{id}', 'show')->name('tracking-products.show');
        Route::post('/{id}', 'update')->name('tracking-products.update');
        Route::delete('/{id}', 'destroy')->name('tracking-products.destroy');
    });
});
