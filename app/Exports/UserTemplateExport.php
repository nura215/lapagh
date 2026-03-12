<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UserTemplateExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return ['nama', 'nip', 'status_pegawai', 'jabatan'];
    }

    public function collection(): Collection
    {
        return collect([
            ['Adi Wijaya STp', '1970001022025211048', 'PPPK PARUH WAKTU', 'Juru mudi/supir kepala dinas'],
            ['Nenden Nuraini', '', 'non asn', 'Supir'],
        ]);
    }
}
