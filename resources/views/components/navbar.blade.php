<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TuNetic</title>
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Text:wght@400;500;550;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
    </style>
</head>

<body>

    {{-- Navbar --}}
    <div class="navbar" style="padding: 12px 50px;">
        <div style="width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 40px;">
            <div class="logo">
                <img src="{{ asset('assets/images/Masyarakat/logo.png') }}" alt="Logo" style="height: 50px;">
            </div>
            <div class="nav-links">
                <a href="/masyarakat">Home</a>
                <a href="/lapor">Lapor Sampah</a>
                <a href="/armada">Rute Armada</a>
            </div>
            <a href="profil.html" class="profile">
                Fulan Andi Prasetyo
                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Profile">
            </a>
        </div>
    </div>



    {{-- Konten utama --}}
    <main>
        <div style="padding: 0">
            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    <footer style="background-color: #2c3e43; color: white; padding: 40px 60px;">
        <div style="display: flex; justify-content: space-between; max-width: 1100px; margin: auto;">

            <!-- Kiri: Logo + Sosmed -->
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

            <!-- Bungkus semua bagian tengah & kanan -->
            <div style="display: flex; gap: 80px; padding: 10px 0;">
                <!-- Tengah 1 -->
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

                <!-- Tengah 2 -->
                <div>
                    <h4 style="margin-bottom: 15px;">Layanan</h4>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 10px;"><a href="#" style="color: white; text-decoration: none;">Jemput
                                Sampah</a></li>
                        <li style="margin-bottom: 10px;"><a href="#" style="color: white; text-decoration: none;">Lapor
                                Sampah</a></li>
                    </ul>

                </div>

                <!-- Kanan -->
                <div style="margin-bottom: 30px;">
                    <h4 style="margin-bottom: 15px;">Contact</h4>
                    <p style="margin: 10px 0;"><i class="fas fa-map-marker-alt"></i> Semarang, Indonesia</p>
                    <p style="margin: 10px 0;"><i class="fas fa-envelope"></i> TuNetic@gmail.com</p>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>