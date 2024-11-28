<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [App\Http\Controllers\AuthController::class, 'index'])->name('login');
Route::post('login', [App\Http\Controllers\AuthController::class, 'store'])->name('login.submit');
