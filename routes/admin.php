<?php

use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\LaporanController;
use App\Http\Controllers\admin\PasswordController;
use App\Http\Controllers\admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::get('/users/upload', [UserController::class, 'upload'])->name('users.upload');
        Route::post('/users/upload', [UserController::class, 'import'])->name('users.import');
        Route::get('/users/template', [UserController::class, 'downloadTemplate'])->name('users.template');
        Route::get('/users/download', [UserController::class, 'downloadAccounts'])->name('users.download');
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/download', [LaporanController::class, 'download'])->name('laporan.download');
        Route::get('/laporan/download-bulanan', [LaporanController::class, 'downloadBulanan'])->name('laporan.download.bulanan');
        Route::get('/laporan/{laporan}', [LaporanController::class, 'show'])->name('laporan.show');

        Route::post('/password', [PasswordController::class, 'update'])->name('password.update');
    });
