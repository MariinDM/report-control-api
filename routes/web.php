<?php

use App\Http\Middleware\RenewToken;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return response()->json(['message' => 'Please login to access this functionality.'], 401);
})->name('login')
->middleware([RenewToken::class]);
