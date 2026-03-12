<aside class="sidebar">
    <nav class="nav">
        <a class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <svg class="nav-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-5h-4v5H5a1 1 0 0 1-1-1v-9.5Z" fill="currentColor"/></svg>
            <span>Dasbor</span>
        </a>
        <a class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
            <svg class="nav-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm8 6c0-2.21-3.58-3-8-3s-8 .79-8 3v3h16v-3Zm3-4c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2Zm-3 5v-1c0-.86.5-1.6 1.25-2 .7-.38 1.62-.59 2.75-.59 1.12 0 2.05.21 2.75.59.75.4 1.25 1.14 1.25 2v1H16Z" fill="currentColor"/></svg>
            <span>User</span>
        </a>
        <a class="nav-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" href="{{ route('admin.laporan.index') }}">
            <svg class="nav-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 3h7l5 5v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Zm7 1.5V9h4.5L13 4.5ZM8 11h8v1.5H8V11Zm0 3h8v1.5H8V14Zm0 3h5v1.5H8V17Z" fill="currentColor"/></svg>
            <span>Laporan</span>
        </a>
    </nav>
</aside>
