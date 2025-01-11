<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('login', [App\Http\Controllers\AuthController::class, 'index'])->name('login');
Route::get('logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::get('auth/{provider}', [AuthController::class, 'redirectToProvider']);
Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback']);

Route::middleware('auth')->group(function () {
    Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('hr-graph', function () {
    return view('hr-graph');
});

Route::get('heart-rate-data', function () {
    $data = App\Models\RealTimeVitals::orderBy('timestamp', 'desc')->get(); // Latest 10 entries

    return response()->json($data);
});

// Route::post('login', [App\Http\Controllers\AuthController::class, 'store'])->name('login.submit');
