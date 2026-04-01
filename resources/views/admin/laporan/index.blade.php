@extends('layouts.admin')


@section('title', 'Laporan')

@section('content')
    <div class="breadcrumb">dasbor / laporan</div>
    <h1 class="page-title">Laporan</h1>

    <div class="toolbar">
        <div class="toolbar-left">
            <form method="GET" action="{{ route('admin.laporan.index') }}">
                <input class="input search" type="text" name="q" placeholder="Search...." value="{{ $search }}">
            </form>
        </div>
        <div class="toolbar-right">
            <button class="btn btn-outline" type="button" id="downloadTrigger">Download Laporan</button>
        </div>
    </div>

    <div class="table-scroll">
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Status Pegawai</th>
                    <th>Jabatan</th>
                    <th>Isi Laporan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporan as $item)
                    <tr>
                        <td>{{ $item->tanggal?->translatedFormat('d F Y') }}</td>
                        <td>{{ $item->pegawai?->nama }}</td>
                        <td>{{ $item->pegawai?->nip ?? '-' }}</td>
                        <td>{{ $item->pegawai?->status_pegawai ?? '-' }}</td>
                        <td>{{ $item->pegawai?->jabatan ?? '-' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($item->isi_laporan, 35) }}</td>
                        <td>
                            <a href="{{ route('admin.laporan.show', $item) }}">Lihat</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">Belum ada laporan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

<div class="table-footer">
    <div>
        Menampilkan {{ $laporan->firstItem() ?? 0 }}–{{ $laporan->lastItem() ?? 0 }} dari {{ $laporan->total() }} data
    </div>
    <div>
        {{ $laporan->onEachSide(1)->links('pagination::bootstrap-4') }}
    </div>
</div>

<!-- Modal pilih bulan -->
<div class="modal" id="monthModal">
    <div class="modal-dialog" style="max-width:360px;">
        <div class="modal-body">
            <h4 style="margin:0 0 10px 0;">Pilih Bulan</h4>
            <form method="GET" action="{{ route('admin.laporan.download') }}" id="downloadForm">
                <input class="input" type="month" id="monthPicker" value="{{ now()->format('Y-m') }}" style="width:100%; margin-bottom:12px;">
                <input type="hidden" name="bulan" id="bulanField" value="{{ now()->month }}">
                <input type="hidden" name="tahun" id="tahunField" value="{{ now()->year }}">
                <div style="display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Download</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/laporan.css') }}">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const monthPicker = document.getElementById('monthPicker');
    const bulanField = document.getElementById('bulanField');
    const tahunField = document.getElementById('tahunField');
    const trigger = document.getElementById('downloadTrigger');
    const modal = document.getElementById('monthModal');
    const form = document.getElementById('downloadForm');

    const syncMonth = () => {
        if (!monthPicker.value) return;
        const [year, month] = monthPicker.value.split('-').map(Number);
        bulanField.value = month;
        tahunField.value = year;
    };

    monthPicker.addEventListener('change', syncMonth);

    trigger?.addEventListener('click', (e) => {
        e.preventDefault();
        modal.style.display = 'flex';
        modal.classList.add('show');
    });

    modal?.addEventListener('click', (e) => {
        if (e.target === modal || e.target.hasAttribute('data-modal-close')) {
            modal.classList.remove('show');
            modal.style.display = 'none';
        }
    });

    syncMonth();
});
</script>
@endpush
