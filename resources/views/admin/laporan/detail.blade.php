@extends('layouts.admin')

@section('content')
    <div class="breadcrumb">dasbor &gt; Laporan</div>
    <h1 class="page-title">Detail Laporan</h1>

    <div class="card">
        <div class="section-label">Identitas</div>
        <div class="identity-list" style="margin-bottom: 16px;">
            <div><strong>Nama</strong> {{ $laporan->pegawai?->nama }}</div>
            <div><strong>NIP</strong> {{ $laporan->pegawai?->nip ?? '-' }}</div>
            <div><strong>Status Pegawai</strong> {{ $laporan->pegawai?->status_pegawai ?? '-' }}</div>
            <div><strong>Jabatan</strong> {{ $laporan->pegawai?->jabatan ?? '-' }}</div>
            <div><strong>Tanggal</strong> {{ $laporan->tanggal?->translatedFormat('d F Y') }}</div>
        </div>

        <div class="section-label">Laporan Kinerja Harian (LKH)</div>
        <p>{!! nl2br(e($laporan->isi_laporan)) !!}</p>

        <div class="section-label">Bukti Pendukung</div>
        @if ($laporan->bukti->count())
            <div class="table-actions">
                @foreach ($laporan->bukti as $item)
                    <a class="btn btn-outline" href="{{ \Illuminate\Support\Facades\Storage::url($item->file_path) }}" target="_blank">Lihat Bukti</a>
                @endforeach
            </div>
        @else
            <p class="form-note">Belum ada bukti yang diunggah.</p>
        @endif
    </div>
@endsection
