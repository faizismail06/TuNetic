{{-- File: resources/views/layouts/landing.blade.php --}}
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
</html>