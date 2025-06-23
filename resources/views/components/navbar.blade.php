<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TuNetic')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Text:wght@400;500;550;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS - TAMBAHAN PENTING -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
            background-color: #f8f9fa;
        }

        /* Navbar Base Styles */
        ..navbar {
            position: fixed;
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
            min-height: 80px;
            transition: transform 0.3s ease-in-out;
            /* Sudah ada */
        }

        .navbar-hide {
            top: -100%;
        }

        .navbar-content {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
        }

        .logo img {
            height: 50px;
            transition: all 0.3s ease;
            margin-left: 20px;
        }

        .logo img:hover {
            transform: scale(1.05);
        }

        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #333;
            padding: 8px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .mobile-menu-toggle:hover {
            background-color: #f5f5f5;
        }

        /* Navigation Links */
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
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-links a:hover {
            color: #299E63;
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

        .nav-links a.active::after {
            width: 100%;
        }

        /* Dropdown Styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown>a {
            display: inline-block;
            position: relative;
        }

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

        .dropdown>a.active::after {
            width: 100%;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #ffffff;
            min-width: 200px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            border-radius: 12px;
            overflow: hidden;
            top: 100%;
            left: 0;
            border: 1px solid #e5e5e5;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a {
            color: #333;
            padding: 14px 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            border-bottom: 1px solid #f5f5f5;
        }

        .dropdown-content a:last-child {
            border-bottom: none;
        }

        .dropdown-content a:hover {
            background-color: #f8f9fa;
            color: #299E63;
        }

        .dropdown-content a.active {
            background-color: #299E63;
            color: white;
        }

        .dropdown-content a.active:hover {
            background-color: #248c55;
        }

        /* Profile Styles */
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
            border-radius: 12px;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
        }

        .profile:hover {
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .profile img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #ffffff;
            min-width: 220px;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            border: 1px solid #e5e5e5;
            overflow: hidden;
            margin-top: 8px;
        }

        .profile-dropdown.show {
            display: block;
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
            padding: 20px;
            border-bottom: 1px solid #e5e5e5;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .profile-dropdown-header .user-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
            font-size: 16px;
        }

        .profile-dropdown-header .user-role {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #299E63;
            color: white;
            padding: 4px 8px;
            border-radius: 20px;
            display: inline-block;
        }

        .profile-dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            border-bottom: 1px solid #f5f5f5;
        }

        .profile-dropdown-item:last-child {
            border-bottom: none;
        }

        .profile-dropdown-item:hover {
            background-color: #f8f9fa;
            padding-left: 24px;
        }

        .profile-dropdown-item i {
            width: 18px;
            text-align: center;
            color: #666;
            transition: color 0.3s ease;
        }

        .profile-dropdown-item:hover i {
            color: #299E63;
        }

        .profile-dropdown-item.logout:hover {
            background-color: #fff5f5;
            color: #dc3545;
        }

        .profile-dropdown-item.logout:hover i {
            color: #dc3545;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-dialog {
            margin: 10% auto;
            max-width: 420px;
            margin-top: 100px;
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        footer {
            background-color: #2c3e43;
            color: white;
            padding: 40px 60px;
            margin-top: 50px;
        }

        .footer-container {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: auto;
            flex-wrap: wrap;
            gap: 40px;
        }

        .footer-left {
            display: flex;
            flex-direction: column;
            gap: 15px;
            min-width: 200px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-logo img {
            height: 50px;
            transition: transform 0.3s ease;
        }

        .footer-logo img:hover {
            transform: scale(1.05);
        }

        .footer-social {
            display: flex;
            gap: 15px;
            font-size: 25px;
            margin-left: 15px;
        }

        .footer-social a {
            color: white;
            transition: transform 0.3s ease, color 0.3s ease;
            text-decoration: none;
        }

        .footer-social a:hover {
            transform: scale(1.2);
            color: #299E63;
        }

        .footer-links {
            display: flex;
            gap: 60px;
            flex-wrap: wrap;
        }

        .footer-column {
            min-width: 150px;
        }

        .footer-column h4 {
            margin-bottom: 20px;
            color: #299E63;
            font-size: 18px;
        }

        .footer-column ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-column li {
            margin-bottom: 12px;
        }

        .footer-column a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease, padding-left 0.3s ease;
        }

        .footer-column a:hover {
            color: #299E63;
            padding-left: 5px;
        }

        .footer-contact {
            min-width: 200px;
        }

        .footer-contact p {
            margin: 10px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-contact i {
            width: 20px;
            color: #299E63;
        }

        .footer-copyright {
            border-top: 1px solid #495057;
            margin-top: 30px;
            padding-top: 20px;
            text-align: center;
            color: #adb5bd;
        }

        /* Footer Responsive Design */
        @media (max-width: 1024px) {
            footer {
                padding: 35px 40px;
            }

            .footer-container {
                gap: 35px;
            }

            .footer-links {
                gap: 40px;
            }
        }

        @media (max-width: 768px) {
            footer {
                padding: 30px 25px;
            }

            .footer-container {
                flex-direction: column;
                gap: 30px;
                text-align: center;
            }

            .footer-left {
                align-items: center;
                min-width: auto;
            }

            .footer-social {
                margin-left: 0;
                justify-content: center;
            }

            .footer-links {
                justify-content: center;
                gap: 30px;
            }

            .footer-column {
                min-width: 120px;
                text-align: center;
            }

            .footer-contact {
                min-width: auto;
                text-align: center;
            }

            .footer-contact p {
                justify-content: center;
            }
        }

        @media (max-width: 600px) {
            footer {
                padding: 25px 20px;
            }

            .footer-container {
                gap: 25px;
            }

            .footer-logo img {
                height: 40px;
            }

            .footer-social {
                font-size: 22px;
                gap: 12px;
            }

            .footer-links {
                flex-direction: column;
                gap: 25px;
                align-items: center;
            }

            .footer-column {
                min-width: auto;
                width: 100%;
            }

            .footer-column h4 {
                font-size: 16px;
                margin-bottom: 15px;
            }

            .footer-column a {
                font-size: 14px;
            }

            .footer-contact {
                width: 100%;
            }

            .footer-contact p {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            footer {
                padding: 20px 15px;
                margin-top: 30px;
            }

            .footer-container {
                gap: 20px;
            }

            .footer-logo img {
                height: 35px;
            }

            .footer-social {
                font-size: 20px;
                gap: 10px;
            }

            .footer-links {
                gap: 20px;
            }

            .footer-column h4 {
                font-size: 15px;
                margin-bottom: 12px;
            }

            .footer-column li {
                margin-bottom: 8px;
            }

            .footer-column a {
                font-size: 13px;
            }

            .footer-contact p {
                font-size: 13px;
                margin: 8px 0;
            }

            .footer-copyright {
                margin-top: 20px;
                padding-top: 15px;
            }

            .footer-copyright p {
                font-size: 12px;
            }
        }

        /* Extra small devices */
        @media (max-width: 360px) {
            footer {
                padding: 15px 10px;
            }

            .footer-logo img {
                height: 30px;
            }

            .footer-social {
                font-size: 18px;
            }

            .footer-column h4 {
                font-size: 14px;
            }

            .footer-column a {
                font-size: 12px;
            }

            .footer-contact p {
                font-size: 12px;
            }
        }

        .modal-content {
            position: relative;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            padding: 0;
            overflow: hidden;
        }

        .modal-header {
            padding: 24px;
            border-bottom: 1px solid #e5e5e5;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .modal-header h5 {
            margin: 0;
            font-weight: 600;
            color: #333;
            font-size: 18px;
        }

        .modal-body {
            padding: 32px 24px;
            text-align: center;
        }

        .modal-body p {
            color: #666;
            font-size: 16px;
            line-height: 1.5;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 20px 24px;
            border-top: 1px solid #e5e5e5;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            border: none;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-default {
            background-color: #e9ecef;
            color: #495057;
            border: 1px solid #ced4da;
        }

        .btn-default:hover {
            background-color: #dee2e6;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .navbar {
                padding: 18px 35px;
                gap: 25px;
            }

            .navbar-content {
                gap: 25px;
            }

            .nav-links {
                gap: 25px;
                margin-right: 25px;
            }

            .logo img {
                height: 45px;
            }
        }

        @media (max-width: 1024px) {
            .navbar {
                padding: 15px 30px;
                gap: 20px;
                min-height: 70px;
            }

            .navbar-content {
                gap: 20px;
            }

            .nav-links {
                gap: 20px;
                margin-right: 20px;
            }

            .logo img {
                height: 42px;
            }

            .profile {
                padding: 6px 10px;
                font-size: 14px;
            }

            .profile img {
                width: 32px;
                height: 32px;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                flex-wrap: wrap;
                padding: 15px 20px;
                gap: 15px;
                min-height: auto;
            }

            .navbar-content {
                flex-wrap: wrap;
                gap: 15px;
                width: 100%;
            }

            .mobile-menu-toggle {
                display: block;
                order: 2;
                margin-left: auto;
            }

            .logo {
                order: 1;
                flex-shrink: 0;
            }

            .logo img {
                height: 40px;
            }

            .profile-container {
                order: 3;
                flex-shrink: 0;
            }

            .nav-links {
                display: none;
                width: 100%;
                flex-direction: column;
                gap: 0;
                margin: 15px 0 0 0;
                order: 4;
                background: #f8f9fa;
                border-radius: 12px;
                padding: 15px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                animation: slideDown 0.3s ease;
            }

            .nav-links.active {
                display: flex;
            }

            .nav-links a {
                padding: 15px 12px;
                border-bottom: 1px solid #e5e5e5;
                border-radius: 8px;
                margin-bottom: 8px;
                transition: all 0.3s ease;
                font-size: 15px;
            }

            .nav-links a:last-child {
                border-bottom: none;
                margin-bottom: 0;
            }

            .nav-links a:hover {
                background-color: #ffffff;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .nav-links a::after {
                display: none;
            }

            .dropdown-content {
                position: static;
                display: block;
                box-shadow: none;
                border: none;
                background: #e9ecef;
                margin-top: 8px;
                border-radius: 8px;
            }

            .dropdown-content a {
                padding: 12px 16px;
                font-size: 14px;
            }

            .profile {
                padding: 8px 12px;
                font-size: 14px;
                min-width: auto;
            }

            .profile img {
                width: 30px;
                height: 30px;
            }

            .profile-dropdown {
                min-width: 200px;
                right: 0;
                left: auto;
            }

            .profile-dropdown-header {
                padding: 16px;
            }

            .profile-dropdown-item {
                padding: 14px 16px;
                font-size: 14px;
            }
        }

        @media (max-width: 600px) {
            .navbar {
                padding: 12px 16px;
            }

            .navbar-content {
                gap: 12px;
            }

            .logo img {
                height: 36px;
            }

            .profile span {
                display: none;
            }

            .profile {
                padding: 8px;
                border-radius: 50%;
                width: 46px;
                height: 46px;
                justify-content: center;
                min-width: 46px;
            }

            .profile img {
                width: 30px;
                height: 30px;
            }

            .nav-links {
                padding: 12px;
                margin: 12px 0 0 0;
            }

            .nav-links a {
                padding: 12px 10px;
                font-size: 14px;
            }

            .dropdown-content a {
                padding: 10px 14px;
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                padding: 10px 12px;
            }

            .logo img {
                height: 32px;
            }

            .profile {
                width: 42px;
                height: 42px;
            }

            .profile img {
                width: 26px;
                height: 26px;
            }

            .mobile-menu-toggle {
                font-size: 20px;
                padding: 6px;
            }
        }

        /* Loading Animation */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #299E63;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body>
    @stack('css')
    @stack('js')

    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-content">
            <div class="logo">
                @php
                    use Illuminate\Support\Facades\Auth;
                    $userLevel = Auth::check() ? Auth::user()->level : null;
                    $homeUrl = '/masyarakat'; // default

                    if ($userLevel == 3) {
                        $homeUrl = '/petugas';
                    } elseif ($userLevel == 4) {
                        $homeUrl = '/masyarakat';
                    }
                @endphp
                <a href="{{ url($homeUrl) }}">
                    <img src="{{ asset('assets/images/Masyarakat/Logo.png') }}" alt="Logo">
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

            @if (Auth::check())
                <div class="profile-container" style="margin-right: 20px;">
                    <div class="profile" id="profileToggle">
                        <span>{{ Auth::user()->name }}</span>
                        <img src="{{ Auth::user()->gambar ? asset('storage/profile/' . Auth::user()->gambar) : asset('assets/images/icons/user0.png') }}"
                            alt="Profile">
                        <i class="fas fa-chevron-down" style="font-size: 12px; margin-left: 4px;"></i>
                    </div>
                    <div class="profile-dropdown" id="profileDropdown">
                        <div class="profile-dropdown-header">
                            <div class="user-name">{{ Auth::user()->name }}</div>
                            <div class="user-role">
                                @if ($userLevel == 3)
                                    petugas
                                @elseif($userLevel == 4)
                                    masyarakat
                                @else
                                    guest
                                @endif
                            </div>
                        </div>

                        @php
                            // Dynamic profile route based on user level
                            if ($userLevel == 3) {
                                $profileRoute = route('petugas.profile.index');
                                $accountRoute = route('petugas.akun.index');
                            } elseif ($userLevel == 4) {
                                $profileRoute = route('masyarakat.profile.index');
                                $accountRoute = route('masyarakat.akun.index');
                            }
                        @endphp

                        <a href="{{ $profileRoute }}" class="profile-dropdown-item">
                            <i class="fas fa-user"></i>
                            Detail Profile
                        </a>

                        <a href="{{ $accountRoute }}" class="profile-dropdown-item">
                            <i class="fas fa-id-card"></i>
                            Ubah Password
                        </a>

                        {{-- Hanya tampilkan menu "Jadi Petugas" jika user level bukan 3 --}}
                        @if ($userLevel != 3)
                            <a href="{{ route('masyarakat.jadi-petugas.form') }}" class="profile-dropdown-item">
                                <i class="fas fa-user-shield"></i>
                                Jadi Petugas
                            </a>
                        @endif

                        <a href="#" class="profile-dropdown-item logout" id="logoutBtn">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="profile">
                    <span>Login</span>
                    <img src="{{ asset('assets/images/icons/user0.png') }}" alt="Profile">
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
    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <!-- Left: Logo + Social -->
            <div class="footer-left">
                <div class="footer-logo">
                    <img src="{{ asset('assets/images/Masyarakat/LogoPutih.png') }}" alt="TuNetic Logo">
                </div>
                <div class="footer-social">
                    <a href="#" aria-label="Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>

            <!-- Middle & Right -->
            <div class="footer-links">
                <!-- Middle 1 -->
                <div class="footer-column">
                    <h4>TuNetic</h4>
                    <ul>
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Layanan</a></li>
                        <li><a href="#">TPS</a></li>
                        <li><a href="#">Jadwal</a></li>
                        <li><a href="#">Edukasi</a></li>
                    </ul>
                </div>

                <!-- Middle 2 -->
                <div class="footer-column">
                    <h4>Layanan</h4>
                    <ul>
                        <li><a href="#">Jemput Sampah</a></li>
                        @php
                            $laporRoute = $userLevel == 3 ? '/petugas/lapor' : '/masyarakat/lapor';
                        @endphp
                        <li><a href="{{ url($laporRoute) }}">Lapor Sampah</a></li>
                    </ul>
                </div>

                <!-- Right -->
                <div class="footer-column footer-contact">
                    <h4>Contact</h4>
                    <p><i class="fas fa-map-marker-alt"></i> Semarang, Indonesia</p>
                    <p><i class="fas fa-envelope"></i> TuNetic@gmail.com</p>
                    <p><i class="fas fa-phone"></i> +62 123 456 789</p>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="footer-copyright">
            <p>&copy; 2024 TuNetic. All rights reserved.</p>
        </div>
    </footer>


    {{--
    <!-- Pastikan urutan script benar -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script> --}}

    <script src="{{ asset('') }}plugins/jquery/jquery.min.js"></script>
    <script src="{{ asset('') }}plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('') }}plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <script src="{{ asset('') }}plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('') }}plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('') }}plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{ asset('') }}plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('') }}plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('') }}plugins/jszip/jszip.min.js"></script>
    <script src="{{ asset('') }}plugins/pdfmake/pdfmake.min.js"></script>
    <script src="{{ asset('') }}plugins/pdfmake/vfs_fonts.js"></script>
    <script src="{{ asset('') }}plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('') }}plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('') }}plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    @stack('js')
    <script src="{{ asset('') }}dist/js/adminlte.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle functionality
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const navLinks = document.getElementById('navLinks');

            if (mobileMenuToggle && navLinks) {
                mobileMenuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    navLinks.classList.toggle('active');

                    // Change icon
                    const icon = this.querySelector('i');
                    if (navLinks.classList.contains('active')) {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-times');
                    } else {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });

                // Close mobile menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!navLinks.contains(event.target) && !mobileMenuToggle.contains(event.target)) {
                        navLinks.classList.remove('active');
                        const icon = mobileMenuToggle.querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });

                // Close mobile menu when window is resized to desktop
                window.addEventListener('resize', function() {
                    if (window.innerWidth > 768) {
                        navLinks.classList.remove('active');
                        const icon = mobileMenuToggle.querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });
            }

            // Profile dropdown functionality
            const profileToggle = document.getElementById('profileToggle');
            const profileDropdown = document.getElementById('profileDropdown');

            if (profileToggle && profileDropdown) {
                profileToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    profileDropdown.classList.toggle('show');

                    // Rotate chevron icon
                    const chevron = this.querySelector('.fa-chevron-down');
                    if (chevron) {
                        if (profileDropdown.classList.contains('show')) {
                            chevron.style.transform = 'rotate(180deg)';
                        } else {
                            chevron.style.transform = 'rotate(0deg)';
                        }
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!profileToggle.contains(event.target) && !profileDropdown.contains(event.target)) {
                        profileDropdown.classList.remove('show');
                        const chevron = profileToggle.querySelector('.fa-chevron-down');
                        if (chevron) {
                            chevron.style.transform = 'rotate(0deg)';
                        }
                    }
                });

                // Close dropdown when pressing Escape key
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && profileDropdown.classList.contains('show')) {
                        profileDropdown.classList.remove('show');
                        const chevron = profileToggle.querySelector('.fa-chevron-down');
                        if (chevron) {
                            chevron.style.transform = 'rotate(0deg)';
                        }
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
                    e.stopPropagation();

                    // Close profile dropdown
                    if (profileDropdown) {
                        profileDropdown.classList.remove('show');
                        const chevron = profileToggle?.querySelector('.fa-chevron-down');
                        if (chevron) {
                            chevron.style.transform = 'rotate(0deg)';
                        }
                    }

                    // Show logout modal
                    logoutModal.style.display = "block";
                    document.body.style.overflow = 'hidden'; // Prevent background scrolling
                });
            }

            if (cancelLogout && logoutModal) {
                cancelLogout.addEventListener('click', function(e) {
                    e.preventDefault();
                    logoutModal.style.display = "none";
                    document.body.style.overflow = 'auto'; // Restore scrolling
                });
            }

            // Close modal when clicking outside
            if (logoutModal) {
                logoutModal.addEventListener('click', function(event) {
                    if (event.target === logoutModal) {
                        logoutModal.style.display = "none";
                        document.body.style.overflow = 'auto'; // Restore scrolling
                    }
                });

                // Close modal when pressing Escape key
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && logoutModal.style.display === 'block') {
                        logoutModal.style.display = "none";
                        document.body.style.overflow = 'auto'; // Restore scrolling
                    }
                });
            }

            // Smooth scroll for anchor links
            const anchorLinks = document.querySelectorAll('a[href^="#"]');
            anchorLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        e.preventDefault();
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add loading state to forms
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitButtons = form.querySelectorAll(
                        'button[type="submit"], input[type="submit"]');
                    submitButtons.forEach(button => {
                        button.classList.add('loading');
                        button.disabled = true;

                        // Re-enable after 5 seconds as fallback
                        setTimeout(() => {
                            button.classList.remove('loading');
                            button.disabled = false;
                        }, 5000);
                    });
                });
            });

            // Add active state management for navigation
            const currentPath = window.location.pathname;
            const navLinksElements = document.querySelectorAll('.nav-links a, .dropdown-content a');

            navLinksElements.forEach(link => {
                const linkPath = new URL(link.href).pathname;
                if (linkPath === currentPath) {
                    link.classList.add('active');

                    // If it's in a dropdown, also mark the parent as active
                    const dropdown = link.closest('.dropdown');
                    if (dropdown) {
                        const parentLink = dropdown.querySelector('> a');
                        if (parentLink) {
                            parentLink.classList.add('active');
                        }
                    }
                }
            });

            // Intersection Observer for animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe elements that should animate on scroll
            const animateElements = document.querySelectorAll('.modal-content, .profile-dropdown');
            animateElements.forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(el);
            });

            // Performance optimization: Debounce scroll events
            let scrollTimeout;

            function debounceScroll(func, wait) {
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(scrollTimeout);
                        func(...args);
                    };
                    clearTimeout(scrollTimeout);
                    scrollTimeout = setTimeout(later, wait);
                };
            }

            // Navbar scroll effect
            let lastScrollTop = 0;
            const navbar = document.querySelector('.navbar');

            const handleScroll = debounceScroll(() => {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    // // Scrolling down & past threshold
                    // navbar.style.transform = 'translateY(-100%)';
                    // Scroll ke bawah, sembunyikan navbar
                    navbar.classList.add('navbar-hide');
                } else {
                    // // Scrolling up
                    // navbar.style.transform = 'translateY(0)';
                    // Scroll ke atas, tampilkan navbar
                    navbar.classList.remove('navbar-hide');
                }

                lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
            }, 10);

            window.addEventListener('scroll', handleScroll);

            // Add transition to navbar for smooth hide/show
            if (navbar) {
                navbar.style.transition = 'transform 0.3s ease-in-out';
            }

            // Preload important images
            const importantImages = [
                "{{ asset('assets/images/Masyarakat/Logo.png') }}",
                "{{ asset('assets/images/Masyarakat/LogoPutih.png') }}",
                // "{{ asset('assets/images/icons/user1.png') }}"
            ];

            importantImages.forEach(src => {
                const img = new Image();
                img.src = src;
            });

            // Service Worker registration (if available)
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js')
                        .then(registration => {
                            console.log('SW registered: ', registration);
                        })
                        .catch(registrationError => {
                            console.log('SW registration failed: ', registrationError);
                        });
                });
            }
        });

        // Global error handler
        window.addEventListener('error', function(event) {
            console.error('Global error:', event.error);
            // You can send this to your logging service
        });

        // Handle uncaught promise rejections
        window.addEventListener('unhandledrejection', function(event) {
            console.error('Unhandled promise rejection:', event.reason);
            // You can send this to your logging service
        });
    </script>

    <!-- Additional scripts -->
    @stack('scripts')
</body>

</html>
