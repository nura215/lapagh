<?php

namespace App\Http\Controllers\pegawai;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('pegawai');
        $latestLaporan = $user->pegawai?->laporan()->latest('tanggal')->first();

        return view('pegawai.dashboard.index', [
            'user' => $user,
            'latestLaporan' => $latestLaporan,
        ]);
    }
}
