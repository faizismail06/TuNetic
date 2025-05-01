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
        }

        .profile img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }

        .profile:hover {
            opacity: 0.8;
            cursor: pointer;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 8px;
            overflow: hidden;
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

        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #333;
        }
    </style>
</head>

<body>
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
                @php
                    $menus = json_decode(MenuHelper::Menu());
                    $userMenu = null;

                    // Find the user menu
                    foreach($menus as $menu) {
                        if($menu->nama_menu === 'Menu User') {
                            $userMenu = $menu;
                            break;
                        }
                    }
                @endphp

                @if($userMenu)
                    @foreach($userMenu->submenus as $submenu)
                        @php
                            // Extract route path
                            $route = $submenu->url;
                            $routeSegment = explode('/', $route)[1] ?? '';

                            // Check if current route matches this menu item
                            $isActive = Request::segment(1) === $routeSegment;

                            // // Map menu items to icons
                            // $iconMap = [
                            //     'Home' => '',
                            //     'Laporan Sampah' => '',
                            //     'Rute Armada' => 'fas fa-route',
                            //     'Profile' => 'fas fa-user'
                            // ];

                            $icon = $iconMap[$submenu->nama_menu] ?? $submenu->icon;
                        @endphp

                        @if(count($submenu->submenus) == 0)
                            <a href="{{ url($submenu->url) }}" class="{{ $isActive ? 'active' : '' }}">
                                <i class="{{ $icon }}"></i> {{ $submenu->nama_menu }}
                            </a>
                        @else
                            <div class="dropdown">
                                <a href="#" class="{{ $isActive ? 'active' : '' }}">
                                    <i class="{{ $icon }}"></i> {{ $submenu->nama_menu }}
                                </a>
                                <div class="dropdown-content">
                                    @foreach($submenu->submenus as $childMenu)
                                        <a href="{{ url($childMenu->url) }}">
                                            <i class="{{ $childMenu->icon }}"></i> {{ $childMenu->nama_menu }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <!-- Fallback navigation if menu structure isn't found -->
                    <a href="{{ url('/masyarakat') }}" class="{{ Request::is('masyarakat') ? 'active' : '' }}">
                        <i class=""></i> Home
                    </a>
                    <a href="{{ url('/masyarakat/lapor') }}" class="{{ Request::is('lapor*') ? 'active' : '' }}">
                        <i class=""></i> Lapor Sampah
                    </a>
                    <a href="{{ url('/armada') }}" class="{{ Request::is('armada*') ? 'active' : '' }}">
                        <i class=""></i> Rute Armada
                    </a>
                    <div class="dropdown">
                        <a href="#" class="{{ Request::is('profile*') ? 'active' : '' }}">
                            <i class=""></i> Profile
                        </a>
                        <div class="dropdown-content">
                            <a href="{{ url('/user/profile') }}">
                                <i class="fas fa-id-card"></i> Lihat Profile
                            </a>
                            <a href="{{ url('/user/settings') }}">
                                <i class="fas fa-cog"></i> Pengaturan
                            </a>
                            <a href="{{ url('/logout') }}" style="color: #dc3545;">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- User Profile Picture & Name -->
            <a href="{{ url('/user/profile') }}" class="profile">
                <span>{{ Auth::user()->name ?? 'Fulan Andi Prasetyo' }}</span>
                <img src="{{ Auth::user()->profile_photo_url ?? 'https://randomuser.me/api/portraits/men/32.jpg' }}" alt="Profile">
            </a>
        </div>
    </div>

    <!-- Content -->
    <main>
        <div style="padding: 0">
            @yield('content')
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
                        <li style="margin-bottom: 10px;"><a href="#" style="color: white; text-decoration: none;">Jemput
                                Sampah</a></li>
                        <li style="margin-bottom: 10px;"><a href="{{ url('/masyarakat/lapor') }}" style="color: white; text-decoration: none;">Lapor
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
        });
    </script>

    <!-- Additional scripts -->
    @yield('scripts')
</body>

</html>
