<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Exports\LaporanBulananExport;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('q')->trim()->toString();

        $laporan = Laporan::with('pegawai.user', 'bukti')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('isi_laporan', 'like', "%{$search}%")
                        ->orWhereHas('pegawai', function ($pegawai) use ($search) {
                            $pegawai->where('nama', 'like', "%{$search}%")
                                ->orWhere('nip', 'like', "%{$search}%")
                                ->orWhere('status_pegawai', 'like', "%{$search}%")
                                ->orWhere('jabatan', 'like', "%{$search}%")
                                ->orWhereHas('user', function ($user) use ($search) {
                                    $user->where('username', 'like', "%{$search}%");
                                });
                        });
                });
            })
            ->orderByDesc('tanggal')
            ->paginate(20)
            ->withQueryString();

        return view('admin.laporan.index', [
            'laporan' => $laporan,
            'search' => $search,
        ]);
    }

    public function show(Laporan $laporan)
    {
        $laporan->load('pegawai.user', 'bukti');

        return view('admin.laporan.detail', [
            'laporan' => $laporan,
        ]);
    }

    public function downloadBulanan(Request $request)
    {
        $month = (int) $request->input('bulan', now()->month);
        $year  = (int) $request->input('tahun', now()->year);

        $filename = sprintf('laporan-bulanan-%04d-%02d.xlsx', $year, $month);

        return Excel::download(new LaporanBulananExport($month, $year), $filename);
    }

    public function download()
    {
        $month = (int) request()->input('bulan', now()->month);
        $year  = (int) request()->input('tahun', now()->year);

        $filename = sprintf('laporan-%04d-%02d.xlsx', $year, $month);

        return Excel::download(new LaporanBulananExport($month, $year), $filename);
    }
}
