@extends('layouts.admin')

@section('title', 'Dasbor')

@section('content')

<h1 class="page-title">Selamat Datang, Admin</h1>

@if(session('success'))
<div class="alert success">
    {{ session('success') }}
</div>
@endif

<div class="toolbar">

    <div class="toolbar-left">
        <form method="GET" action="{{ route('admin.dashboard') }}">
            <input
                class="input search"
                type="text"
                name="q"
                placeholder="Search...."
                value="{{ $search }}"
            >
        </form>
    </div>

    <div class="toolbar-right">
        <a class="btn btn-outline" href="{{ route('admin.users.download') }}">
            Download
        </a>
    </div>

</div>


<div class="table-wrapper">

    <div class="table-scroll">

        <table class="table">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Status Pegawai</th>
                    <th>Jabatan</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

            @forelse($users as $index => $user)

            <tr>
                <td>{{ ($users->firstItem() ?? 0) + $index }}</td>
                <td>{{ $user->pegawai?->nama ?? '-' }}</td>
                <td>{{ $user->pegawai?->nip ?? '-' }}</td>
                <td>{{ $user->pegawai?->status_pegawai ?? '-' }}</td>
                <td>{{ $user->pegawai?->jabatan ?? '-' }}</td>
                <td>{{ $user->username }}</td>
                {{-- Tampilkan password acak selama belum diganti (is_first_login == true). Jika sudah diganti, kolom kosong. --}}
                <td>{{ $user->is_first_login ? ($user->temp_password ?? '') : '' }}</td>

                <td>
                    <form method="POST" action="{{ route('admin.users.reset',$user->id) }}">
                        @csrf
                        <button class="btn btn-outline" type="submit">
                            Reset Password
                        </button>
                    </form>
                </td>
            </tr>

            @empty

            <tr>
                <td colspan="7">Belum ada data pegawai.</td>
            </tr>

            @endforelse

            </tbody>

        </table>
    </div>

</div>

<div class="table-footer">
    <div>
        Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
    </div>
    <div>
        {{ $users->onEachSide(1)->links('pagination::bootstrap-4') }}
    </div>
</div>

@endsection
