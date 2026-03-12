@extends('layouts.admin')

@section('content')
    <div class="breadcrumb">dasbor &gt; User</div>
    <h1 class="page-title">Edit User</h1>

    @if ($errors->any())
        <div class="alert error">{{ $errors->first() }}</div>
    @endif

    <div class="card" style="max-width: 560px;">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')
            <div class="form-row">
                <label class="form-label" for="nama">Nama</label>
                <input class="input" id="nama" name="nama" type="text" value="{{ old('nama', $user->pegawai?->nama) }}" required>
            </div>
            <div class="form-row">
                <label class="form-label" for="nip">NIP</label>
                <input class="input" id="nip" name="nip" type="text" value="{{ old('nip', $user->pegawai?->nip) }}">
                <div class="form-note">Jika NIP diisi, username akan menggunakan NIP.</div>
            </div>
            <div class="form-row">
                <label class="form-label" for="status_pegawai">Status Pegawai</label>
                <input class="input" id="status_pegawai" name="status_pegawai" type="text" value="{{ old('status_pegawai', $user->pegawai?->status_pegawai) }}" required>
            </div>
            <div class="form-row">
                <label class="form-label" for="jabatan">Jabatan</label>
                <input class="input" id="jabatan" name="jabatan" type="text" value="{{ old('jabatan', $user->pegawai?->jabatan) }}" required>
            </div>
            <div class="form-row">
                <button class="btn btn-primary" type="submit">Simpan</button>
            </div>
        </form>
    </div>
@endsection
