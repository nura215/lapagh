@extends('layouts.admin')

@section('title', 'Detail')

@section('content')
    <div class="breadcrumb">dasbor / Laporan</div>
    <h1 class="page-title">Detail Laporan</h1>

    <div class="card">
        <div class="section-lprn" style="color:#2563eb; ">Identitas</div>

        <table class="identity-table">

        <tr>
        <td class="label"><strong>Nama</strong></td>
        <td>: {{ $laporan->pegawai?->nama }}</td>
        </tr>

        <tr>
        <td class="label"><strong>NIP</strong></td>
        <td>: {{ $laporan->pegawai?->nip ?? '-' }}</td>
        </tr>

        <tr>
        <td class="label"><strong>Status Pegawai</strong></td>
        <td>: {{ $laporan->pegawai?->status_pegawai ?? '-' }}</td>
        </tr>

        <tr>
        <td class="label"><strong>Jabatan</strong></td>
        <td>: {{ $laporan->pegawai?->jabatan ?? '-' }}</td>
        </tr>

        <tr>
        <td class="label"><strong>Tanggal</strong></td>
        <td>: {{ $laporan->tanggal?->translatedFormat('d F Y') }}</td>
        </tr>

        </table>

        <div class="section-lprn " style="color:#2563eb; margin-top:10px; ">Laporan Kinerja Harian (LKH)</div>
        <p>{!! nl2br(e($laporan->isi_laporan)) !!}</p>

        <div class="section-lprn" style=" margin-top:10px; margin-bottom:10px">Bukti Pendukung</div>
        @if ($laporan->bukti->count())
            <div class="table-actions">
                @foreach ($laporan->bukti as $item)
                    <a class="btn btn-outline" href="{{ $item->url }}" target="_blank">Lihat Bukti</a>
                @endforeach
            </div>
        @else
            <p class="form-note">Belum ada bukti yang diunggah.</p>
        @endif
    </div>
@endsection
