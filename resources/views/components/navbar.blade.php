@php
    $user = auth()->user();
    $isPns = filled($user?->pegawai?->nip);
    $displayName = $isPns
        ? ($user->pegawai?->nama ?? $user->username ?? 'User')
        : ($user->username ?? 'User');
    $photoUrl = $user?->pegawai?->foto_url;
@endphp

<header class="topbar">
    <div class="topbar-left">
        <button class="burger" type="button" aria-label="Buka menu" data-toggle="sidebar">
            <span class="burger-line"></span>
            <span class="burger-line"></span>
            <span class="burger-line"></span>
        </button>
        <div class="topbar-logo" aria-label="LAPAR">
            <img src="{{ asset('images/logo.png') }}" alt="LAPAR">
        </div>
    </div>
    <div class="topbar-user">
        <button class="user-button" type="button" aria-label="Menu pengguna" data-toggle="user-menu">
            <span class="user-name-inline">{{ $displayName }}</span>
            <span class="user-avatar">
                @if ($photoUrl)
                    <img src="{{ $photoUrl }}" alt="Foto profil">
                @else
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm0 2c-3.33 0-6 1.34-6 3v1h12v-1c0-1.66-2.67-3-6-3Z" fill="#1f4b99"/>
                    </svg>
                @endif
            </span>
        </button>
        <div class="user-menu" id="user-menu">
            @if ($user?->isAdmin())
                <button class="user-menu-item" type="button" data-toggle="change-password-modal">
                    <span class="profile-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 3a5 5 0 0 0-5 5v2h10V8a5 5 0 0 0-5-5Zm-7 9a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2Z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="16" r="1.2" fill="currentColor"/>
                        </svg>
                    </span>
                    <span class="logout-text">Ubah Password</span>
                    <span class="logout-chevron" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="m9 6 6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </button>
            @endif
            @if ($user?->pegawai)
                <button class="user-menu-item" type="button" data-toggle="profile-modal">
                    <span class="profile-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm0 2c-3.33 0-6 1.34-6 3v1h12v-1c0-1.66-2.67-3-6-3Z" fill="currentColor"/>
                        </svg>
                    </span>
                    <span class="logout-text">Edit Profil</span>
                    <span class="logout-chevron" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="m9 6 6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </button>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="user-menu-item" type="submit">
                    <span class="logout-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 3a7 7 0 0 0-7 7v3a7 7 0 0 0 7 7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="M12 3v4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="m17 12-2 2m2-2-2-2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span class="logout-text">Logout</span>
                    <span class="logout-chevron" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="m9 6 6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </button>
            </form>
        </div>
    </div>
</header>

@if ($user?->pegawai)
    <div class="profile-modal" id="profile-modal" aria-hidden="true">
        <div class="profile-modal__backdrop" data-close="profile-modal"></div>
        <div class="profile-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="profile-modal-title">
            <div class="profile-modal__header">
                <div>
                    <div id="profile-modal-title" class="profile-modal__title">Edit Profil</div>
                    <div class="profile-modal__subtitle">Data hanya bisa dilihat, unggah foto untuk memperbarui profil.</div>
                </div>
                <button type="button" class="profile-modal__close" data-close="profile-modal" aria-label="Tutup modal">x</button>
            </div>
            <form method="POST" action="{{ route('pegawai.profile.photo') }}" enctype="multipart/form-data" class="profile-modal__body">
                @csrf
                <div class="profile-modal__grid">
                    <label class="profile-field">
                        <span>Nama</span>
                        <input class="input" type="text" value="{{ $user->pegawai->nama }}" readonly>
                    </label>
                    <label class="profile-field">
                        <span>NIP</span>
                        <input class="input" type="text" value="{{ $user->pegawai->nip ?? '-' }}" readonly>
                    </label>
                    <label class="profile-field">
                        <span>Status Pegawai</span>
                        <input class="input" type="text" value="{{ $user->pegawai->status_pegawai }}" readonly>
                    </label>
                    <label class="profile-field">
                        <span>Jabatan</span>
                        <input class="input" type="text" value="{{ $user->pegawai->jabatan }}" readonly>
                    </label>
                </div>

                <div class="profile-photo-actions">
                    <label class="profile-dropzone" for="foto_profile_input">
                        <div class="profile-dropzone__icon">
                            <svg class="icon icon-cloud" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M7 18.5a4 4 0 0 1-.2-8 5 5 0 0 1 9.6-1.6A3.5 3.5 0 1 1 17.5 18H13" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 14v7m0 0 3-3m-3 3-3-3" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="profile-dropzone__text">
                            <div class="profile-dropzone__title" id="foto_profile_name">Klik untuk memilih foto</div>
                            <div class="profile-dropzone__subtitle">JPG/PNG maks 4 MB</div>
                        </div>
                        <input type="file" name="foto_profile" accept="image/*" class="visually-hidden" id="foto_profile_input" required>
                    </label>
                    <p class="profile-note">Foto akan tampil pada avatar.</p>
                </div>

                <div class="profile-modal__actions">
                    <button type="button" class="btn profile-cancel" data-close="profile-modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Foto</button>
                </div>
            </form>
        </div>
    </div>
@endif

@if ($user?->isAdmin())
    <div class="profile-modal" id="change-password-modal" aria-hidden="true">
        <div class="profile-modal__backdrop" data-close="change-password-modal"></div>
        <div class="profile-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="change-password-title">
            <div class="profile-modal__header">
                <div>
                    <div id="change-password-title" class="profile-modal__title">Ubah Password</div>
                    <div class="profile-modal__subtitle">Masukkan password lama dan buat password baru.</div>
                </div>
                <button type="button" class="profile-modal__close" data-close="change-password-modal" aria-label="Tutup modal">x</button>
            </div>
            <form method="POST" action="{{ route('admin.password.update') }}" class="profile-modal__body">
                @csrf
                <div class="profile-modal__grid">
                    <label class="profile-field">
                        <span>Password Lama</span>
                        <div class="password-wrapper">
                            <input type="password" name="current_password" required autocomplete="current-password" placeholder="Password lama">
                            <i class="fa-solid fa-eye-slash toggle-password"></i>
                        </div>
                    </label>
                    <label class="profile-field">
                        <span>Password Baru</span>
                        <div class="password-wrapper">
                            <input type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                            <i class="fa-solid fa-eye-slash toggle-password"></i>
                        </div>
                    </label>
                    <label class="profile-field">
                        <span>Konfirmasi Password Baru</span>
                        <div class="password-wrapper">
                            <input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password baru">
                            <i class="fa-solid fa-eye-slash toggle-password"></i>
                        </div>
                    </label>
                </div>
                <div class="profile-modal__actions">
                    <button type="button" class="btn profile-cancel" data-close="change-password-modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Password</button>
                </div>
            </form>
        </div>
    </div>
@endif
