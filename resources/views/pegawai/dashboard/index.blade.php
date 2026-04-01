@extends('layouts.pegawai')

@section('title', 'Dasbor')

@section('content')
    <div class="breadcrumb">dasbor / Laporan</div>
    <h1 class="page-title">Laporan Kinerja Harian (LKH)</h1>

    @if (session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert error">{{ $errors->first() }}</div>
    @endif

    <div class="grid-2">
        <div class="card identity-panel">
            <div class="section-heading identity-heading">
                <svg class="icon icon-user" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false">
                    <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm0 2c-3.33 0-6 1.34-6 3v1h12v-1c0-1.66-2.67-3-6-3Z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="identitas_pegawai">Identitas Pagawai</span>
            </div>
            <div class="identity-card identity-with-photo">
                @php
                    $photoUrl = $user->pegawai?->foto_url;
                    $initial = strtoupper(mb_substr($user->pegawai?->nama ?? $user->username ?? 'U', 0, 1));
                @endphp
                <div class="identity-photo">
                    @if ($photoUrl)
                        <img src="{{ $photoUrl }}" alt="Foto profil pegawai">
                    @else
                        <div class="identity-photo__initial">{{ $initial }}</div>
                    @endif
                </div>
                <div class="identity-list">
                    <div class="identity-row">
                        <strong>Nama</strong>
                        <span class="identity-value">{{ $user->pegawai?->nama ?? '-' }}</span>
                    </div>
                    <div class="identity-row">
                        <strong>NIP</strong>
                        <span class="identity-value">{{ $user->pegawai?->nip ?? '-' }}</span>
                    </div>
                    <div class="identity-row">
                        <strong>Status Pegawai</strong>
                        @php $status = $user->pegawai?->status_pegawai ?? '-'; @endphp
                        <span class="identity-value">{{ $status }}</span>
                    </div>
                    <div class="identity-row">
                        <strong>Jabatan</strong>
                        <span class="identity-value">{{ $user->pegawai?->jabatan ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card lkh-card">
            <form method="POST" action="{{ route('pegawai.laporan.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-section">
                    <div class="section-heading">
                        <svg class="icon icon-file" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false">
                            <path d="M7 3h6l5 5v10a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V6a3 3 0 0 1 3-3Z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13 3v5h5" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 12h6M9 16h6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Isi Laporan</span>
                    </div>
                    <textarea class="textarea" id="isi_laporan" name="isi_laporan" placeholder="Tulis laporan harian Anda di sini..." required>{{ old('isi_laporan') }}</textarea>
                </div>
                <div class="form-section">
                    <div class="section-heading">
                        <svg class="icon icon-link" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false">
                            <path d="M10 13a5 5 0 0 1 0-7l2-2a5 5 0 1 1 7 7l-2 2" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14 11a5 5 0 0 1 0 7l-2 2a5 5 0 1 1-7-7l2-2" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Upload Bukti Pendukung</span>
                    </div>

                    <input class="visually-hidden" id="bukti" name="bukti[]" type="file" multiple accept=".jpg,.jpeg,.png,.heic,image/*">
                    <label class="upload-dropzone" for="bukti">
                        <div class="upload-icon">
                            <svg class="icon icon-cloud" viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false">
                                <path d="M7 18.5a4 4 0 0 1-.2-8 5 5 0 0 1 9.6-1.6A3.5 3.5 0 1 1 17.5 18H13" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 14v7m0 0 3-3m-3 3-3-3" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="upload-text">
                            <div class="upload-title">Klik untuk <span class="upload-link">memilih file</span></div>
                            <div class="upload-subtitle">JPG, JPEG, PNG, HEIC — Maks 5 foto</div>
                        </div>
                    </label>
                    <div id="preview-bukti" class="preview-grid"></div>
                </div>
                <button class="btn btn-primary" type="submit">Kirim Laporan</button>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user/dasbor.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/user.js') }}"></script>
@endpush
