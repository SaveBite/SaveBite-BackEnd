<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\OtpController;
use App\Http\Controllers\Api\V1\Auth\PasswordController;
use App\Http\Controllers\Api\V1\LoginAnswer\LoginAnswerController;
use App\Http\Controllers\Api\V1\Product\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function () {
    Route::group(['prefix' => 'sign'], function () {
        Route::post('in', 'signIn');
        Route::post('up', 'signUp');
        Route::post('out', 'signOut');
    });

});
Route::group(['prefix' => 'otp','controller' => OtpController::class], function () {
    Route::get('/', 'send');
    Route::post('/verify', 'verify');
});
Route::get('login_answers',[LoginAnswerController::class,'index']);

Route::group(['controller' => PasswordController::class], function () {
    Route::post('lost-image','forgetPassword');
    Route::post('lost-image-check-code','checkCode');
//    Route::post('reset-password','resetPassword');
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('products/upload', [ProductController::class, 'upload'])->name('products.upload');
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::post('products/', [ProductController::class, 'store'])->name('products.store');
    Route::get('stock', [ProductController::class, 'stock'])->name('products.stock');
});
