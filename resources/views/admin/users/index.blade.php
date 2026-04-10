@extends('layouts.admin')


@section('title', 'User')
@section('content')
    <div class="breadcrumb">dasbor / User</div>
    <h1 class="page-title">Daftar User</h1>

    @if (session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    <div class="toolbar">
        <div class="toolbar-left">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <input class="input search" type="text" name="q" placeholder="Search...." value="{{ $search }}">
                <input type="hidden" name="sort" value="{{ $sortBy }}">
                <input type="hidden" name="dir" value="{{ $sortDir }}">
            </form>
        </div>
        <div class="toolbar-right">
            <a class="btn btn-outline" href="{{ route('admin.users.create') }}">Input Manual</a>
            <a class="btn btn-outline" href="{{ route('admin.users.upload') }}">Upload Excel</a>
        </div>
    </div>

    <div class="table-scroll">
        @php
            $sortLink = function (string $column) use ($search, $sortBy, $sortDir) {
                return route('admin.users.index', [
                    'q' => $search !== '' ? $search : null,
                    'sort' => $column,
                    'dir' => $sortBy === $column && $sortDir === 'asc' ? 'desc' : 'asc',
                ]);
            };

            $sortClass = function (string $column) use ($sortBy, $sortDir) {
                if ($sortBy !== $column) {
                    return '';
                }

                return $sortDir === 'asc' ? 'active asc' : 'active desc';
            };
        @endphp
        <table class="table" id="tableUser">
            <thead>
                <tr>
                    <th>No</th>
                    <th>
                        <a class="sort-trigger {{ $sortClass('nama') }}" href="{{ $sortLink('nama') }}">
                            <span>Nama</span>
                            <span class="sort-icon" aria-hidden="true"></span>
                        </a>
                    </th>
                    <th>
                        <a class="sort-trigger {{ $sortClass('nip') }}" href="{{ $sortLink('nip') }}">
                            <span>NIP</span>
                            <span class="sort-icon" aria-hidden="true"></span>
                        </a>
                    </th>
                    <th>
                        <a class="sort-trigger {{ $sortClass('status_pegawai') }}" href="{{ $sortLink('status_pegawai') }}">
                            <span>Status Pegawai</span>
                            <span class="sort-icon" aria-hidden="true"></span>
                        </a>
                    </th>
                    <th>
                        <a class="sort-trigger {{ $sortClass('jabatan') }}" href="{{ $sortLink('jabatan') }}">
                            <span>Jabatan</span>
                            <span class="sort-icon" aria-hidden="true"></span>
                        </a>
                    </th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index => $user)
                    <tr>
                        <td>{{ ($users->firstItem() ?? 0) + $index }}</td>
                        <td>{{ $user->pegawai?->nama }}</td>
                        <td>{{ $user->pegawai?->nip ?? '-' }}</td>
                        <td>{{ $user->pegawai?->status_pegawai ?? '-' }}</td>
                        <td>{{ $user->pegawai?->jabatan ?? '-' }}</td>
                        <td class="table-actions">
                            <a class="icon-btn" href="{{ route('admin.users.edit', $user) }}" title="Ubah">
                                <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path d="M4 17.25V20h2.75l8.1-8.1-2.75-2.75L4 17.25Zm14.71-9.04c.2-.2.2-.51 0-.71l-2.21-2.21a.5.5 0 0 0-.71 0l-1.83 1.83 2.75 2.75 2-1.66Z" fill="currentColor"/></svg>
                            </a>
                            <button class="icon-btn danger" type="button" data-user="{{ $user->id }}" data-name="{{ $user->pegawai?->nama ?? $user->username }}">
                                <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path d="M9 3h6a1 1 0 0 1 1 1v1h4v1.5H4V5h4V4a1 1 0 0 1 1-1Zm-3 5h12l-.8 11.2a1 1 0 0 1-1 .9H7.8a1 1 0 0 1-1-.9L6 8Z" fill="currentColor"/></svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Belum ada data pegawai.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <div>
            Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
        </div>
        <div>
            {{ $users->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>
    <div class="modal" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-body">
                <p id="deleteText">Hapus user?</p>
                <div class="modal-actions">
                    <button class="btn btn-outline" type="button" data-modal-close>Batal</button>
                    <form method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-primary danger" type="submit">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .icon-btn {
        border: 1px solid var(--stroke);
        border-radius: 8px;
        padding: 6px 10px;
        background: #fff;
        cursor: pointer;
        font-size: 14px;
    }
    .icon-btn.danger { border-color: #e26b6b; }
    .modal {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.35);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 99;
    }
    .modal.show { display: flex; }
    .modal-dialog {
        background: #fff;
        border-radius: 12px;
        padding: 18px;
        width: 360px;
        box-shadow: 0 12px 24px rgba(0,0,0,0.15);
    }
    .modal-body p { margin-top: 0; margin-bottom: 16px; }
    .modal-actions { display: flex; justify-content: flex-end; gap: 10px; }
    .btn-primary.danger { background: #e26b6b; border-color: #c95454; color: #fff; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.icon-btn.danger');
        const closeBtn = e.target.closest('[data-modal-close]');
        const modal = document.getElementById('deleteModal');

        if (btn) {
            const id = btn.getAttribute('data-user');
            const name = btn.getAttribute('data-name');
            document.getElementById('deleteText').textContent = `Hapus user "${name}"?`;
            const form = document.getElementById('deleteForm');
            form.action = `{{ url('admin/users') }}/${id}`;
            modal.classList.add('show');
        }

        if (closeBtn || (modal && e.target === modal)) {
            modal.classList.remove('show');
        }
    });

</script>
@endpush
