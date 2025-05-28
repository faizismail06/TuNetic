<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel - Pengelolaan Sampah Tembalang</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #28a745;
            --sidebar-width: 250px;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
        }
        
        #sidebar {
            position: fixed;
            width: var(--sidebar-width);
            height: 100%;
            background-color: var(--primary-color);
            color: white;
            overflow-y: auto;
            z-index: 100;
            transition: all 0.3s;
        }
        
        #sidebar .sidebar-header {
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.1);
        }
        
        #sidebar .sidebar-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        #content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .menu-item:hover, .menu-item.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .menu-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            background-color: rgba(0, 0, 0, 0.1);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        
        .submenu.show {
            max-height: 500px;
        }
        
        .submenu .menu-item {
            padding-left: 50px;
        }
        
        .dropdown-indicator {
            margin-left: auto;
            transition: transform 0.3s;
        }
        
        .dropdown-indicator.rotated {
            transform: rotate(180deg);
        }
        
        .navbar-top {
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-top .user-profile {
            display: flex;
            align-items: center;
        }
        
        .navbar-top .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-left: 10px;
        }
        
        .content-header {
            margin-bottom: 20px;
        }
        
        .card {
            margin-bottom: 20px;
        }
        
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            
            #content {
                margin-left: 0;
            }
            
            #sidebar.active {
                margin-left: 0;
            }
            
            #content.active {
                margin-left: var(--sidebar-width);
            }
            
            .menu-toggle {
                display: block;
            }
        }
        
        .notification-bell {
            position: relative;
            font-size: 1.25rem;
            color: #6c757d;
        }
        
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    
    @stack('css')
</head>
<body>
    <div id="sidebar">
        <div class="sidebar-header">
            <h3>ADMIN PANEL</h3>
        </div>
        
        <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        
        <a href="#manajemenData" class="menu-item dropdown-toggle" data-toggle="collapse">
            <i class="fas fa-database"></i> Manajemen Data
            <i class="fas fa-chevron-down dropdown-indicator"></i>
        </a>
        <div class="submenu {{ request()->routeIs('data.*') ? 'show' : '' }}" id="manajemenData">
            <a href="{{ route('data.warga') }}" class="menu-item {{ request()->routeIs('data.warga') ? 'active' : '' }}">Data Warga</a>
            <a href="{{ route('data.petugas') }}" class="menu-item {{ request()->routeIs('data.petugas') ? 'active' : '' }}">Data Petugas</a>
            <a href="{{ route('data.tps') }}" class="menu-item {{ request()->routeIs('data.tps') ? 'active' : '' }}">Data TPS</a>
            <a href="{{ route('data.armada') }}" class="menu-item {{ request()->routeIs('data.armada') ? 'active' : '' }}">Data Armada</a>
        </div>
        
        <a href="{{ route('laporan.pengaduan') }}" class="menu-item {{ request()->routeIs('laporan.pengaduan') ? 'active' : '' }}">
            <i class="fas fa-flag"></i> Laporan Pengaduan
        </a>
        
        <a href="{{ route('artikel.index') }}" class="menu-item {{ request()->routeIs('artikel.*') ? 'active' : '' }}">
            <i class="fas fa-newspaper"></i> Artikel
        </a>
        
        <a href="#jadwalArmada" class="menu-item dropdown-toggle" data-toggle="collapse">
            <i class="fas fa-calendar-alt"></i> Jadwal Armada
            <i class="fas fa-chevron-down dropdown-indicator"></i>
        </a>
        <div class="submenu {{ request()->routeIs('jadwal.*') ? 'show' : '' }}" id="jadwalArmada">
            <a href="{{ route('jadwal.harian') }}" class="menu-item {{ request()->routeIs('jadwal.harian') ? 'active' : '' }}">Jadwal Harian</a>
            <a href="{{ route('jadwal.mingguan') }}" class="menu-item {{ request()->routeIs('jadwal.mingguan') ? 'active' : '' }}">Jadwal Mingguan</a>
        </div>
        
        <a href="{{ route('perhitungan.sampah') }}" class="menu-item {{ request()->routeIs('perhitungan.sampah') ? 'active' : '' }}">
            <i class="fas fa-calculator"></i> Perhitungan Sampah
        </a>
        
        <a href="{{ route('pengaturan') }}" class="menu-item {{ request()->routeIs('pengaturan') ? 'active' : '' }}">
            <i class="fas fa-cog"></i> Pengaturan
        </a>
        
        <a href="{{ route('profil') }}" class="menu-item {{ request()->routeIs('profil') ? 'active' : '' }}">
            <i class="fas fa-user"></i> Profil
        </a>
    </div>
    
    <div id="content">
        <nav class="navbar-top">
            <button class="menu-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="user-profile">
                <div class="notification-bell mr-4">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">1</span>
                </div>
                <span>{{ auth()->user()->name }}</span>
                <img src="{{ asset('images/avatar.jpg') }}" alt="User Avatar">
            </div>
        </nav>
        
        @yield('content')
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Toggle submenu
            $('.dropdown-toggle').click(function(e) {
                e.preventDefault();
                const submenu = $($(this).attr('href'));
                submenu.toggleClass('show');
                $(this).find('.dropdown-indicator').toggleClass('rotated');
            });
            
            // Toggle sidebar on mobile
            $('#sidebarToggle').click(function() {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
            });
            
            // Active route for sidebar
            const currentRoute = window.location.pathname;
            $('.menu-item').each(function() {
                const href = $(this).attr('href');
                if (href && currentRoute.includes(href) && href !== '#') {
                    $(this).addClass('active');
                    $(this).parents('.submenu').addClass('show');
                    $(this).parents('.submenu').prev('.dropdown-toggle').find('.dropdown-indicator').addClass('rotated');
                }
            });
        });
    </script>
    
    @stack('js')
</body>
</html>