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
            'bukti.*' => ['file', 'mimes:jpg,jpeg,png,heic,pdf'],
        ], [
            'bukti.max' => 'Maksimal 5 file bukti pendukung.',
            'bukti.*.file' => 'Salah satu berkas bukti tidak valid.',
            'bukti.*.mimes' => 'Format file bukti harus JPG, JPEG, PNG, HEIC, atau PDF.',
        ]);

        $files = $request->file('bukti', []);
        $pdfCount = 0;
        $uploadErrors = [];
        if (is_array($files) && count($files)) {
            foreach ($files as $file) {
                if (! $file) {
                    continue;
                }

                $originalName = (string) $file->getClientOriginalName();
                $ext = strtolower((string) $file->getClientOriginalExtension());
                $mime = strtolower((string) $file->getMimeType());
                $size = (int) $file->getSize();
                $isPdf = $ext === 'pdf' || str_contains($mime, 'pdf');

                if ($isPdf) {
                    $pdfCount++;
                    if ($size > 5 * 1024 * 1024) {
                        $uploadErrors[] = 'Ukuran file PDF "' . $originalName . '" melebihi 5 MB.';
                    }
                    continue;
                }

                if ($size > 2 * 1024 * 1024) {
                    $uploadErrors[] = 'Ukuran file gambar "' . $originalName . '" melebihi 2 MB.';
                }
            }
        }

        if ($pdfCount > 1) {
            $uploadErrors[] = 'Maksimal 1 file PDF yang bisa diunggah.';
        }

        if (count($uploadErrors)) {
            return back()
                ->withInput()
                ->withErrors(['bukti' => array_values(array_unique($uploadErrors))]);
        }

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

        if (is_array($files) && count($files)) {
            $year = now()->year;
            $month = str_pad((string) now()->month, 2, '0', STR_PAD_LEFT);
            $owner = $pegawai->nip ?: 'user-' . $pegawai->id;
            $baseDir = "bukti/{$year}/{$month}/{$owner}";

            foreach ($files as $index => $file) {
                if (! $file) {
                    continue;
                }

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
