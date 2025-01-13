<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImagerController;
use App\Http\Controllers\RedirectContoller;
use App\Http\Controllers\StorageBoxController;
use Illuminate\Support\Facades\Route;

Route::get('login', [App\Http\Controllers\AuthController::class, 'index'])->name('login');
Route::get('logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::get('auth/{provider}', [AuthController::class, 'redirectToProvider']);
Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback']);

Route::get('open-box/{storageBox}', [StorageBoxController::class, 'show'])->name('open-box');

Route::middleware('auth')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        Route::get('redirect/{to}', [RedirectContoller::class, 'redirect'])->name('redirect');
    
        Route::get('storage-boxes', [StorageBoxController::class, 'index'])->name('storage-boxes');
        Route::post('storage-boxes', [StorageBoxController::class, 'store'])->name('storage-boxes.store');
        Route::get('storage/{storageBox}', [StorageBoxController::class, 'qr'])->name('storage-qr');
    
        Route::get('imager', [ImagerController::class, 'index'])->name('imager');
        Route::post('imager-upload-init', [ImagerController::class, 'initializeUpload'])->name('imager-upload-init');
        Route::post('imager-upload-chunk', [ImagerController::class, 'uploadChunk'])->name('imager-upload-chunk');
        Route::post('imager-upload-finalize', [ImagerController::class, 'finalizeUpload'])->name('imager-upload-finalize');
        Route::post('imager-upload-cancel', [ImagerController::class, 'cancelUpload'])->name('imager-upload-cancel');
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
