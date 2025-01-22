<?php

use App\Http\Controllers\Authentication;
use App\Http\Controllers\ExportFile;
use App\Http\Controllers\Products;
use App\Http\Controllers\Users;
use App\Http\Middleware\RenewToken;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::post('/login', [Authentication::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {

    // Route::middleware(RenewToken::class)->group(function () {

        Route::post('/logout', [Authentication::class, 'logout'])->name('logout');
        
        Route::middleware(RoleMiddleware::class)->group(function () {
    
            Route::get('/users', [Users::class, 'index'])->name('users.index');
            Route::post('/users', [Users::class, 'create'])->name('users.create');
            Route::put('/users/{id}', [Users::class, 'update'])->name('users.update');
            Route::delete('/users/{id}', [Users::class, 'delete'])->name('users.delete');
    
            Route::delete('/products/{id}', [Products::class, 'destroy'])->name('products.destroy');
        });
    
        Route::get('/products', [Products::class, 'index'])->name('products.index');
        Route::get('/products/{id}', [Products::class, 'show'])->name('products.show');
        Route::post('/products', [Products::class, 'store'])->name('products.store');
        Route::post('/products/{id}', [Products::class, 'update'])->name('products.update');
    
        Route::post('/export', [ExportFile::class, 'export'])->name('export');
    // });
});
