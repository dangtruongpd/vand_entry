<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Middleware\ProductPermission;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\StorePermission;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::controller(StoreController::class)->prefix('/store')->group(function () {
        Route::get('', 'index');
        Route::get('/{id}', 'show');
        Route::post('', 'store');
        Route::put('/{id}', 'update')->middleware(StorePermission::class);
        Route::delete('/{id}', 'delete')->middleware(StorePermission::class);
    });

    Route::controller(ProductController::class)->prefix('/product')->group(function () {
        Route::get('', 'index');
        Route::get('/{id}', 'show');
        Route::post('', 'store');
        Route::put('/{id}', 'update')->middleware(ProductPermission::class);
        Route::delete('/{id}', 'delete')->middleware(ProductPermission::class);
    });
});
