<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TuNetic')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Text:wght@400;500;550;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Additional styles -->
    @yield('styles')

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Red Hat Text', sans-serif;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffff;
            padding: 20px 40px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-bottom: none;
            gap: 30px;
            flex-wrap: nowrap;
            position: sticky;
            top: 0;
            z-index: 9999;
            font-family: 'Red Hat Text', sans-serif;
            font-weight: 550;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-wrap: wrap;
                padding: 15px 20px;
                gap: 20px;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .nav-links {
                display: none;
                width: 100%;
                flex-direction: column;
                gap: 15px;
                margin-top: 15px;
            }

            .nav-links.active {
                display: flex;
            }
        }

        .nav-links {
            display: flex;
            gap: 30px;
            flex: 1;
            margin-right: 40px;
        }

        .nav-links a {
            position: relative;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            padding: 10px 0;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -6px;
            transform: translateX(-50%);
            width: 0%;
            height: 3px;
            border-radius: 2px;
            background-color: #299E63;
            transition: width 0.3s ease-in-out;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        /* Perbaikan: Hapus !important dan pastikan hanya menu aktif yang memiliki garis */
        .nav-links a.active::after {
            width: 100%;
        }

        /* Perbaikan untuk dropdown parent yang aktif */
        .dropdown>a.active::after {
            width: 100%;
        }

        .profile-container {
            position: relative;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            white-space: nowrap;
            color: #333;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .profile:hover {
            background-color: #f5f5f5;
        }

        .profile img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #ffffff;
            min-width: 200px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            border: 1px solid #e5e5e5;
            overflow: hidden;
        }

        .profile-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-dropdown::before {
            content: '';
            position: absolute;
            top: -8px;
            right: 20px;
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-bottom: 8px solid #ffffff;
        }

        .profile-dropdown-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e5e5e5;
            background-color: #f8f9fa;
        }

        .profile-dropdown-header .user-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }

        .profile-dropdown-header .user-role {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .profile-dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.2s ease;
            font-weight: 500;
        }

        .profile-dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .profile-dropdown-item i {
            width: 16px;
            text-align: center;
            color: #666;
        }

        .profile-dropdown-item.logout:hover {
            background-color: #fee;
            color: #dc3545;
        }

        .profile-dropdown-item.logout:hover i {
            color: #dc3545;
        }

        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #333;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-dialog {
            margin: 15% auto;
            max-width: 400px;
            margin-top: 100px;
        }

        .modal-content {
            position: relative;
            background-color: #fefefe;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            padding: 0;
            overflow: hidden;
        }

        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e5e5e5;
            background-color: #f8f9fa;
        }

        .modal-header h5 {
            margin: 0;
            font-weight: 600;
            color: #333;
        }

        .modal-body {
            padding: 24px;
            text-align: center;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 20px 24px;
            border-top: 1px solid #e5e5e5;
            background-color: #f8f9fa;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            border: none;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.2s ease;
        }

        .btn-default {
            background-color: #e9ecef;
            color: #495057;
        }

        .btn-default:hover {
            background-color: #dee2e6;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Dropdown menu styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 8px;
            overflow: hidden;
            top: 100%;
            left: 0;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background 0.3s;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown>a {
            display: inline-block;
            position: relative;
        }

        /* Perbaikan: Tambahkan style untuk dropdown parent link */
        .dropdown>a::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -6px;
            transform: translateX(-50%);
            width: 0%;
            height: 3px;
            border-radius: 2px;
            background-color: #299E63;
            transition: width 0.3s ease-in-out;
        }

        .dropdown>a:hover::after {
            width: 100%;
        }

        /* Active state untuk dropdown items */
        .dropdown-content a.active {
            background-color: #299E63;
            color: white;
        }

        .dropdown-content a.active:hover {
            background-color: #248c55;
        }
    </style>
</head>

<body>
    @stack('css')
    @stack('js')

    <!-- Navbar -->
    <div class="navbar" style="padding: 12px 50px;">
        <div style="width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 40px;">
            <div class="logo">
                @php
                    use Illuminate\Support\Facades\Auth;
                    $currentRole = Auth::check() ? Auth::user()->roles->first()->name : 'guest';
                    $homeUrl = '/masyarakat'; // default

                    if ($currentRole === 'petugas') {
                        $homeUrl = '/petugas';
                    } elseif ($currentRole === 'user') {
                        $homeUrl = '/masyarakat';
                    }
                @endphp
                <a href="{{ url($homeUrl) }}">
                    <img src="{{ asset('assets/images/Masyarakat/logo.png') }}" alt="Logo" style="height: 50px;">
                </a>
            </div>

            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>

            <div class="nav-links" id="navLinks">
                @php
                    // Define an array of menu names you want to exclude
                    $excludedMenuNames = ['Profile', 'Akun', 'Jadi Petugas'];
                @endphp

                @foreach (json_decode(MenuHelper::Menu()) as $menu)
                    @foreach ($menu->submenus as $submenu)
                        {{-- Check if the current submenu's name is in the excluded list --}}
                        @if (!in_array(ucwords($submenu->nama_menu), $excludedMenuNames))
                            @if (count($submenu->submenus) == '0')
                                {{-- Tautan Tunggal --}}
                                <a href="{{ url($submenu->url) }}"
                                    class="nav-link {{ Request::is(ltrim($submenu->url, '/')) ? 'active' : '' }}">
                                    <i class="{{ $submenu->icon }}"></i>
                                    {{ ucwords($submenu->nama_menu) }}
                                </a>
                            @else
                                {{-- Dropdown --}}
                                @php
                                    $isDropdownActive = false;
                                    $urls = [];
                                    foreach ($submenu->submenus as $endmenu) {
                                        $urls[] = $endmenu->url;
                                        // Check if any of the submenu URLs match the current request
                                        if (Request::is(ltrim($endmenu->url, '/'))) {
                                            $isDropdownActive = true;
                                        }
                                    }
                                @endphp
                                <div class="dropdown">
                                    <a href="#" class="{{ $isDropdownActive ? 'active' : '' }}">
                                        <i class="{{ $submenu->icon }}"></i>
                                        {{ ucwords($submenu->nama_menu) }}
                                    </a>
                                    <div class="dropdown-content">
                                        @foreach ($submenu->submenus as $endmenu)
                                            <a href="{{ url($endmenu->url) }}"
                                                class="{{ Request::is(ltrim($endmenu->url, '/')) ? 'active' : '' }}">
                                                <i class="far fa-circle"></i>
                                                {{ ucwords($endmenu->nama_menu) }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endforeach
                @endforeach
            </div>

            @if  (Auth::check())
                <div class="masyarakat.profilee-container">
                    <div class="profile" id="profileToggle">
                        <span>{{ Auth::user()->name }}</span>
                        <img src="{{ asset(Auth::user()->photo ?? 'assets/images/default-user.png') }}" alt="Profile">
                        <i class="fas fa-chevron-down" style="font-size: 12px; margin-left: 4px;"></i>
                    </div>
                    <div class="profile-dropdown" id="profileDropdown">
                        <div class="profile-dropdown-header">
                            <div class="user-name">{{ Auth::user()->name }}</div>
                            <div class="user-role">{{ $currentRole }}</div>
                        </div>
                        <a href="{{ route('profil.index') }}" class="profile-dropdown-item">
                            <i class="fas fa-user"></i>
                            Detail Profile
                        </a>
                        <a href="#" class="profile-dropdown-item logout" id="logoutBtn">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="profile">
                    Login
                    <img src="{{ asset('assets/images/default-user.png') }}" alt="Profile">
                </a>
            @endif
        </div>
    </div>

    <!-- Content -->
    <main>
        <div style="padding: 0">
            @yield('content')

            <!-- Logout Modal -->
            <div class="modal" id="modal-logout">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Konfirmasi Logout</h5>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <div class="modal-body">
                                <p>Apakah Anda yakin ingin keluar dari akun?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" id="cancelLogout">Batal</button>
                                <button type="submit" class="btn btn-danger">Ya, Keluar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer style="background-color: #2c3e43; color: white; padding: 40px 60px;">
        <div style="display: flex; justify-content: space-between; max-width: 1100px; margin: auto;">
            <!-- Left: Logo + Social -->
            <div style="display: flex; flex-direction: column; gap: 5px; width: 200px;">
                <div style="display: flex; align-items: center; gap: 5px;">
                    <img src="{{ asset('assets/images/Masyarakat/logoputih.png') }}" alt="TuNetic Logo"
                        style="height: 50px;">
                </div>
                <div style="display: flex; gap: 15px; font-size: 25px; margin-left: 15px; margin-top: 8px;">
                    <a href="#" style="color: white;"><i class="fab fa-facebook"></i></a>
                    <a href="#" style="color: white;"><i class="fab fa-instagram"></i></a>
                    <a href="#" style="color: white;"><i class="fab fa-twitter"></i></a>
                </div>
            </div>

            <!-- Middle & Right -->
            <div style="display: flex; gap: 80px; padding: 10px 0;">
                <!-- Middle 1 -->
                <div>
                    <h4 style="margin-bottom: 15px;">TuNetic</h4>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 10px;"><a href="#"
                                style="color: white; text-decoration: none;">Tentang Kami</a></li>
                        <li style="margin-bottom: 10px;"><a href="#"
                                style="color: white; text-decoration: none;">Layanan</a></li>
                        <li style="margin-bottom: 10px;"><a href="#"
                                style="color: white; text-decoration: none;">TPS</a></li>
                        <li style="margin-bottom: 10px;"><a href="#"
                                style="color: white; text-decoration: none;">Jadwal</a></li>
                        <li style="margin-bottom: 10px;"><a href="#"
                                style="color: white; text-decoration: none;">Edukasi</a></li>
                    </ul>
                </div>

                <!-- Middle 2 -->
                <div>
                    <h4 style="margin-bottom: 15px;">Layanan</h4>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 10px;"><a href="#"
                                style="color: white; text-decoration: none;">Jemput
                                Sampah</a></li>
                        @php
                            $laporRoute = $currentRole === 'petugas' ? '/petugas/lapor' : '/masyarakat/lapor';
                        @endphp
                        <li style="margin-bottom: 10px;"><a href="{{ url($laporRoute) }}"
                                style="color: white; text-decoration: none;">Lapor
                                Sampah</a></li>
                    </ul>
                </div>

                <!-- Right -->
                <div style="margin-bottom: 30px;">
                    <h4 style="margin-bottom: 15px;">Contact</h4>
                    <p style="margin: 10px 0;"><i class="fas fa-map-marker-alt"></i> Semarang, Indonesia</p>
                    <p style="margin: 10px 0;"><i class="fas fa-envelope"></i> TuNetic@gmail.com</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle functionality
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const navLinks = document.getElementById('navLinks');

            if (mobileMenuToggle && navLinks) {
                mobileMenuToggle.addEventListener('click', function() {
                    navLinks.classList.toggle('active');
                });
            }

            // Profile dropdown functionality
            const profileToggle = document.getElementById('profileToggle');
            const profileDropdown = document.getElementById('profileDropdown');

            if (profileToggle && profileDropdown) {
                profileToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    profileDropdown.classList.toggle('show');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!profileToggle.contains(event.target) && !profileDropdown.contains(event.target)) {
                        profileDropdown.classList.remove('show');
                    }
                });
            }

            // Logout modal functionality
            const logoutBtn = document.getElementById('logoutBtn');
            const logoutModal = document.getElementById('modal-logout');
            const cancelLogout = document.getElementById('cancelLogout');

            if (logoutBtn && logoutModal) {
                logoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    profileDropdown.classList.remove('show'); // Close dropdown
                    logoutModal.style.display = "block";
                });
            }

            if (cancelLogout && logoutModal) {
                cancelLogout.addEventListener('click', function() {
                    logoutModal.style.display = "none";
                });
            }

            // Close modal when clicking outside
            if (logoutModal) {
                window.addEventListener('click', function(event) {
                    if (event.target == logoutModal) {
                        logoutModal.style.display = "none";
                    }
                });
            }
        });
    </script>

    <!-- Additional scripts -->
    @yield('scripts')
</body>

</html>
