<?php

use App\Http\Controllers\Authentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [Authentication::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', action: [Authentication::class, 'logout']);
});
