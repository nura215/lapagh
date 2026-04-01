@extends('layouts.admin')


@section('title', 'Upload')

@section('content')
    <div class="breadcrumb">dasbor / user / upload </div>
    <h1 class="page-title">Upload Data User</h1>

    <div class="card upload-box" style="max-width: 600px;">

        <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <label class="form-label" for="excel">Upload File Excel</label>
                <div class="upload-drop" id="dropzone">
                    <input class="file-input" id="excel" name="file" type="file" accept=".xlsx,.xls" required>
                    <div class="upload-illustration" aria-hidden="true">
                        <svg viewBox="0 0 64 48">
                            <path d="M14 38h36a4 4 0 0 0 4-4V18a4 4 0 0 0-4-4h-9l-5-6h-6l-5 6h-9a4 4 0 0 0-4 4v16a4 4 0 0 0 4 4Z" fill="#e9f1ff" stroke="#c3d5f7" stroke-width="1.2"/>
                            <rect x="22" y="20" width="20" height="18" rx="2.5" fill="#2c6be0" opacity="0.12"/>
                            <path d="M32 34V24m0 0-4 4m4-4 4 4" stroke="#2c6be0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="upload-text">
                        <div class="upload-title">Drag &amp; Drop file Excel di sini</div>
                        <div class="upload-sub">atau klik untuk memilih file</div>
                        <div class="upload-format">Format: XLSX / XLS</div>
                        <div class="upload-file-name" id="excel-name">Tidak ada file dipilih</div>
                    </div>
                </div>
                <div class="form-note">Unggah file Excel (.xlsx/.xls) dengan header sesuai template.</div>
            </div>
            <div class="form-actions-inline">
                <button class="btn btn-primary" type="submit">Upload File</button>
                <a class="btn btn-outline" href="{{ route('admin.users.template') }}">Download Template</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('excel');
        const nameEl = document.getElementById('excel-name');
        const drop = document.getElementById('dropzone');
        if (!input || !nameEl || !drop) return;

        const updateName = (file) => {
            nameEl.textContent = file ? file.name : 'Tidak ada file dipilih';
        };

        input.addEventListener('change', (e) => {
            const file = e.target.files && e.target.files[0];
            updateName(file);
        });

        ['dragenter','dragover'].forEach(evt =>
            drop.addEventListener(evt, (e) => {
                e.preventDefault();
                drop.classList.add('is-drag');
            })
        );
        ['dragleave','drop'].forEach(evt =>
            drop.addEventListener(evt, (e) => {
                e.preventDefault();
                drop.classList.remove('is-drag');
            })
        );
        drop.addEventListener('drop', (e) => {
            const files = e.dataTransfer?.files;
            if (!files || !files.length) return;
            input.files = files;
            updateName(files[0]);
        });
        drop.addEventListener('click', () => input.click());
    });
</script>
@endpush
