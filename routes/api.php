<?php

use App\Http\Controllers\Authentication;
use App\Http\Controllers\ExportFile;
use App\Http\Controllers\Products;
use App\Http\Controllers\Users;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::post('/login', [Authentication::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', action: [Authentication::class, 'logout']);
    
    Route::middleware(RoleMiddleware::class)->group(function () {
        Route::get('/users', action: [Users::class, 'index']);
        Route::post('/users', action: [Users::class, 'create']);
        Route::put('/users/{id}', action: [Users::class, 'update']);
        Route::delete('/users/{id}', action: [Users::class, 'delete']);


        Route::delete('/products/{id}', action: [Products::class, 'destroy']);
    });

    Route::get('/products', action: [Products::class, 'index']);
    Route::get('/products/{id}', action: [Products::class, 'show']);
    Route::post('/products', action: [Products::class, 'store']);
    Route::post('/products/{id}', action: [Products::class, 'update']);

    Route::post('/export', action: [ExportFile::class, 'export']);
    
});
