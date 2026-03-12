@php
    $user = auth()->user();
    $isPns = filled($user?->pegawai?->nip);
    $displayName = $isPns
        ? ($user->pegawai?->nama ?? $user->username ?? 'User')
        : ($user->username ?? 'User');
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
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm0 2c-3.33 0-6 1.34-6 3v1h12v-1c0-1.66-2.67-3-6-3Z" fill="#1f4b99"/>
                </svg>
            </span>
        </button>
        <div class="user-menu" id="user-menu">
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
