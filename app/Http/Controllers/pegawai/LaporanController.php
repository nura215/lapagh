<?php

namespace App\Http\Controllers\pegawai;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\LaporanBukti;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'isi_laporan' => ['required', 'string', 'max:2000'],
            'bukti' => ['nullable', 'array', 'max:5'],
            'bukti.*' => ['file', 'max:4096', 'mimes:jpg,jpeg,png,heic'],
        ]);

        $pegawai = $request->user()->pegawai;
        if (! $pegawai) {
            return back()->withErrors(['isi_laporan' => 'Data pegawai belum lengkap.']);
        }

        $today = now()->toDateString();

        $laporan = Laporan::create([
            'pegawai_id' => $pegawai->id,
            'tanggal' => $today,
            'isi_laporan' => $validated['isi_laporan'],
        ]);

        if ($request->hasFile('bukti')) {
            $year = now()->year;
            $month = str_pad((string) now()->month, 2, '0', STR_PAD_LEFT);
            $owner = $pegawai->nip ?: 'user-' . $pegawai->id;
            $baseDir = "bukti/{$year}/{$month}/{$owner}";

            foreach ($request->file('bukti') as $index => $file) {
                $ext = strtolower($file->getClientOriginalExtension());
                $safeExt = $ext ?: 'jpg';
                $name = $today . '_' . ($index + 1) . '.' . $safeExt;
                $path = $file->storeAs($baseDir, $name, 'public');

                LaporanBukti::create([
                    'laporan_id' => $laporan->id,
                    'file_path' => $path,
                ]);
            }
        }

        return back()->with('success', 'Laporan berhasil dikirim.');
    }
}
