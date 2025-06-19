<!-- {{-- File: resources/views/layouts/landing.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TuNetic - Buang Sampah Tanpa Ribet')</title>
    {{-- Semua link CSS dan style landing page Anda ada di sini --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/landing-page.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        {{-- Kode <nav> landing page Anda --}}
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-dark text-white py-5">
        {{-- Kode <footer> landing page Anda --}}
    </footer>

    {{-- Semua script JS landing page Anda --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> -->
{{-- File: resources/views/layouts/landing.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TuNetic - Buang Sampah Tanpa Ribet')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/landing-page.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="layout-publik">

    <header>
        {{-- Di sini seharusnya ada kode lengkap <nav> untuk header Anda --}}
        <nav class="navbar navbar-expand-lg bg-white navbar-light py-1">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('assets/images/logowarna2.png') }}" alt="TuNetic Logo" style="height: 60px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="/#tentang-kami">Tentang Kami</a></li>
                        <li class="nav-item"><a class="nav-link" href="/#layanan">Layanan</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('articles.index') }}">Edukasi</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-dark text-white py-5 footer-custom" style="background-color: #2E3A40;">
        <div class="container">
            <p class="text-center mb-0">&copy; {{ date('Y') }} TuNetic. All Rights Reserved.</p>
            {{-- Anda bisa meletakkan kode lengkap footer Anda di sini --}}
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>