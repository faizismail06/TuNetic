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

        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 100%;
        }

        .profile {
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            white-space: nowrap;
            flex: 1;
            min-width: 0;
            justify-content: flex-end;
            cursor: pointer;
        }

        .profile img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }

        .profile:hover {
            opacity: 0.8;
        }

        .user-dropdown {
            position: relative;
            display: inline-block;
        }

        .user-dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 180px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 8px;
            overflow: hidden;
        }

        .user-dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background 0.3s;
        }

        .user-dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .user-dropdown:hover .user-dropdown-content {
            display: block;
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
            max-width: 300px;
        }

        .modal-content {
            position: relative;
            background-color: #fefefe;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .modal-body {
            text-align: center;
            padding: 10px 0 20px 0;
        }

        .modal-footer {
            display: flex;
            justify-content: space-between;
            padding-top: 15px;
            border-top: 1px solid #e9e9e9;
        }

        .btn {
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            font-weight: 500;
        }

        .btn-default {
            background-color: #f4f4f4;
            color: #333;
        }

        .btn-info {
            background-color: #299E63;
            color: white;
        }

        .btn:hover {
            opacity: 0.85;
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
                <a href="{{ url('/masyarakat') }}">
                    <img src="{{ asset('assets/images/Masyarakat/logo.png') }}" alt="Logo" style="height: 50px;">
                </a>
            </div>

            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>

            <div class="nav-links" id="navLinks">
                @foreach (json_decode(MenuHelper::Menu()) as $menu)
                    @foreach ($menu->submenus as $submenu)
                        @if (count($submenu->submenus) == '0')
                            <a href="{{ url($submenu->url) }}"
                                class="nav-link {{ Request::segment(1) == $submenu->url ? 'active' : '' }}">
                                <i class="{{ $submenu->icon }}"></i>
                                {{ ucwords($submenu->nama_menu) }}
                            </a>
                        @else
                            @php
                                $urls = [];
                                foreach ($submenu->submenus as $url) {
                                    $urls[] = $url->url;
                                }
                            @endphp
                            <div class="dropdown">
                                <a href="#" class="{{ in_array(Request::segment(1), $urls) ? 'active' : '' }}">
                                    <i class="{{ $submenu->icon }}"></i>
                                    {{ ucwords($submenu->nama_menu) }}
                                </a>
                                <div class="dropdown-content">
                                    @foreach ($submenu->submenus as $endmenu)
                                        <a href="{{ url($endmenu->url) }}"
                                            class="{{ Request::segment(1) == $endmenu->url ? 'active' : '' }}">
                                            <i class="far fa-circle"></i>
                                            {{ ucwords($endmenu->nama_menu) }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endforeach
            </div>

            <!-- User Profile Dropdown -->
            <div class="user-dropdown">
                <div class="profile">
                    <span>{{ Auth::user()->name ?? 'Fulan Andi Prasetyo' }}</span>
                    <img src="{{ Auth::user()->profile_photo_url ?? 'https://randomuser.me/api/portraits/men/32.jpg' }}"
                        alt="Profile">
                </div>
                <div class="user-dropdown-content">
                    <a href="{{ url('/user/profile') }}">
                        <i class="fas fa-user"></i> Profil
                    </a>
                    <a href="#" id="logoutBtn">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </a>
                </div>
            </div>
        </div>
    </div>



    <!-- Content -->
    <main>
        <div style="padding: 0">
            @yield('content')
            <!-- Logout Modal -->
            <div class="modal" id="modal-logout" style="z-index: 9999">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <div class="modal-body">
                                <h5>Apakah anda ingin keluar?</h5>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" id="cancelLogout">Tidak</button>
                                <a class="btn btn-info" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Ya, Keluar
                                </a>
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
                        <li style="margin-bottom: 10px;"><a href="{{ url('/masyarakat/lapor') }}"
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
        // Mobile menu toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const navLinks = document.getElementById('navLinks');

            if (mobileMenuToggle && navLinks) {
                mobileMenuToggle.addEventListener('click', function() {
                    navLinks.classList.toggle('active');
                });
            }

            // Logout modal functionality
            const logoutBtn = document.getElementById('logoutBtn');
            const logoutModal = document.getElementById('modal-logout');
            const cancelLogout = document.getElementById('cancelLogout');

            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                logoutModal.style.display = "block";
            });

            cancelLogout.addEventListener('click', function() {
                logoutModal.style.display = "none";
            });

            window.addEventListener('click', function(event) {
                if (event.target == logoutModal) {
                    logoutModal.style.display = "none";
                }
            });
        });
    </script>

    <!-- Additional scripts -->
    @yield('scripts')
</body>

</html>
