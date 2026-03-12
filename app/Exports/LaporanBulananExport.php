<?php

namespace App\Exports;

use App\Models\Laporan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanBulananExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        private int $month,
        private int $year,
    ) {
    }

    public function collection(): Collection
    {
        return Laporan::with(['pegawai.user', 'bukti'])
            ->whereYear('tanggal', $this->year)
            ->whereMonth('tanggal', $this->month)
            ->orderBy('tanggal')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama',
            'NIP',
            'Status Pegawai',
            'Jabatan',
            'Isi Laporan',
            'Link gambar 1',
            'Link gambar 2',
            'Link gambar 3',
            'Link gambar 4',
            'Link gambar 5',
        ];
    }

    public function map($laporan): array
    {
        $links = $laporan->bukti
            ->take(5)
            ->values()
            ->map(function ($bukti, $idx) {
                $relative = Storage::url($bukti->file_path);
                $absolute = rtrim(config('app.url'), '/') . $relative;
                return sprintf('=HYPERLINK("%s","Link %d")', $absolute, $idx + 1);
            })
            ->toArray();

        $links = array_pad($links, 5, '');

        return array_merge([
            optional($laporan->tanggal)->format('d/m/Y'),
            $laporan->pegawai?->nama,
            $laporan->pegawai?->nip,
            $laporan->pegawai?->status_pegawai,
            $laporan->pegawai?->jabatan,
            $laporan->isi_laporan,
        ], $links);
    }
}
