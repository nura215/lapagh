<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\FirstPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('pegawai.dashboard');
    }

    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/ganti-password-awal', [FirstPasswordController::class, 'show'])->name('first-password.show');
    Route::post('/ganti-password-awal', [FirstPasswordController::class, 'update'])->name('first-password.update');
});

require __DIR__ . '/admin.php';
require __DIR__ . '/pegawai.php';
