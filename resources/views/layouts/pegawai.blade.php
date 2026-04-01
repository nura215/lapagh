<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pegawai')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-xh6O/CkQoPOWD1Yw0u5hi1R/F7fs/3Gzdh0dX8GZFODdgNpTiFqouBZfyqCkCmZJLdnOjFkWDXLI4YAl4kYKFg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/lapar.css') }}">
    @stack('styles')
</head>
<body>
    @include('components.navbar', ['title' => 'Pegawai'])
    <div class="sidebar-backdrop" data-toggle="sidebar"></div>
    <div class="app-shell">
        @include('components.sidebar-pegawai')
        <main class="app-content">
            @yield('content')
        </main>
    </div>
    <script>
        document.addEventListener('click', function (event) {
            var sidebarToggle = event.target.closest('[data-toggle="sidebar"]');
            var userToggle = event.target.closest('[data-toggle="user-menu"]');
            var userMenu = document.getElementById('user-menu');
            var profileToggle = event.target.closest('[data-toggle="profile-modal"]');
            var profileClose = event.target.closest('[data-close="profile-modal"]');
            var profileModal = document.getElementById('profile-modal');
            var fileInput = document.getElementById('foto_profile_input');
            var fileNameEl = document.getElementById('foto_profile_name');

            if (fileInput && fileNameEl && fileInput.dataset.bound !== '1') {
                fileInput.addEventListener('change', function () {
                    var name = this.files && this.files[0] ? this.files[0].name : 'Klik untuk memilih foto';
                    fileNameEl.textContent = name;
                });
                fileInput.dataset.bound = '1';
            }

            if (sidebarToggle) {
                document.body.classList.toggle('sidebar-open');
                return;
            }

            if (userToggle && userMenu) {
                userMenu.classList.toggle('open');
                return;
            }

            if (profileToggle && profileModal) {
                if (userMenu) {
                    userMenu.classList.remove('open');
                }
                profileModal.classList.add('open');
                profileModal.setAttribute('aria-hidden', 'false');
                return;
            }

            if (profileClose && profileModal) {
                profileModal.classList.remove('open');
                profileModal.setAttribute('aria-hidden', 'true');
                return;
            }

            if (userMenu && !event.target.closest('.user-menu')) {
                userMenu.classList.remove('open');
            }

        });
    </script>
    @stack('scripts')
</body>
</html>
