<?php

use App\Http\Controllers\Dashboard\Auth\AuthController;
use App\Http\Controllers\Dashboard\Home\HomeController;
use App\Http\Controllers\Dashboard\Mangers\MangerController;
use App\Http\Controllers\Dashboard\Roles\RoleController;
use App\Http\Controllers\Dashboard\Settings\SettingController;
use App\Http\Controllers\Dashboard\User\UserController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath',]
], function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.','middleware' => 'throttle:5,1'], function () {
        Route::get('login', [AuthController::class, '_login'])->name('_login');

        Route::post('login', [AuthController::class, 'login'])->name('login');

        Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/', [HomeController::class, 'index'])->name('/');
        Route::resource('users', UserController::class);
        Route::resource('products', \App\Http\Controllers\Dashboard\Product\ProductController::class)->only(['index', 'show', 'destroy']);
        Route::resource('upcomingreorders', \App\Http\Controllers\Dashboard\UpcomingReorder\UpcomingReorderController::class)->only(['index', 'show', 'destroy']);
        Route::resource('trackingproducts', \App\Http\Controllers\Dashboard\TrackingProduct\TrackingProductController::class)->only(['index', 'show', 'destroy']);
    });
    Route::resource('settings' , SettingController::class)->only('edit','update');
    Route::post('update-password' , [SettingController::class,'updatePassword'])->name('update-password');
    Route::resource('roles',RoleController::class);
    Route::get('role/{id}/managers',[RoleController::class,'mangers'])->name('roles.mangers');
    Route::resource('managers',MangerController::class)->except('show','index');

});
