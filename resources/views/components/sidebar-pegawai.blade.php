<aside class="sidebar">
    <nav class="nav">
        <a class="nav-item {{ request()->routeIs('pegawai.dashboard') ? 'active' : '' }}" href="{{ route('pegawai.dashboard') }}">
            <svg class="nav-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-5h-4v5H5a1 1 0 0 1-1-1v-9.5Z" fill="currentColor"/></svg>
            <span>Dasbor</span>
        </a>
    </nav>
</aside>
