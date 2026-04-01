<?php

use App\Http\Controllers\pegawai\DashboardController;
use App\Http\Controllers\pegawai\LaporanController;
use App\Http\Controllers\pegawai\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:asn,non_asn'])
    ->prefix('pegawai')
    ->name('pegawai.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
        Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    });
