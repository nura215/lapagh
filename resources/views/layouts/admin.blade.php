<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin' }} </title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-xh6O/CkQoPOWD1Yw0u5hi1R/F7fs/3Gzdh0dX8GZFODdgNpTiFqouBZfyqCkCmZJLdnOjFkWDXLI4YAl4kYKFg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/lapar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/dasbor.css') }}">
    @stack('styles')
</head>
<body>
    @include('components.navbar', ['title' => 'Admin'])
    <div class="sidebar-backdrop" data-toggle="sidebar"></div>
    <div class="app-shell">
        @include('components.sidebar-admin')
        <main class="app-content">
            @yield('content')
        </main>
    </div>
    <script>
        document.addEventListener('click', function (event) {
            var sidebarToggle = event.target.closest('[data-toggle="sidebar"]');
            var userToggle = event.target.closest('[data-toggle="user-menu"]');
            var userMenu = document.getElementById('user-menu');

            if (sidebarToggle) {
                document.body.classList.toggle('sidebar-open');
                return;
            }

            if (userToggle && userMenu) {
                userMenu.classList.toggle('open');
                return;
            }

            if (userMenu && !event.target.closest('.user-menu')) {
                userMenu.classList.remove('open');
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-3fjrqteEiiTM75GVo5Y6g2Y8OObL5HnMdE6yZ9u0YhM=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    @stack('scripts')
</body>
</html>
