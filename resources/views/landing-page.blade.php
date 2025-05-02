<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TuNetic - Buang Sampah Tanpa Ribet</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family='Red Hat Text':wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Text:ital,wght@0,300..700;1,300..700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/landing-page.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        html {
            scroll-behavior: smooth;
        }

        #tps {
            scroll-margin-top: 100px;
            /* Sesuaikan dengan tinggi navbar jika fixed */
        }

        /* Jadwal section styles */
        #jadwal h2,
        #jadwal h6 {
            font-family: ''Red Hat Text'', sans-serif;
            font-weight: 700;
        }

        #jadwal p,
        #jadwal small {
            font-family: 'Red Hat Text', sans-serif;
        }

        #jadwal .card {
            border-radius: 12px;
            border: none;
            transition: transform 0.3s ease;
        }

        #jadwal .card:hover {
            transform: translateY(-5px);
        }

        /* Blue horizontal line at the bottom of jadwal section */
        #jadwal::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 5px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg bg-white navbar-light py-1">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <img src="{{ asset('assets/images/logowarna2.png') }}" alt="TuRetic Logo" style="height: 60px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="d-flex justify-content-center align-items-center">
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item" style="font-family: Red Hat Text, sans-serif; font-weight: bolder;">
                                <a class="nav-link" style="font-weight: 600;" href="#tentang-kami">Tentang Kami</a>
                            </li>
                            <li class="nav-item" style="font-family: Red Hat Text, sans-serif;">
                                <a class="nav-link" style="font-weight: 600;" href="#layanan">Layanan</a>
                            </li>
                            <li class="nav-item" style="font-family: Red Hat Text, sans-serif;">
                                <a class="nav-link" style="font-weight: 600;" href="#tps">TPS</a>
                            </li>
                            <li class="nav-item" style="font-family: Red Hat Text, sans-serif;">
                                <a class="nav-link" style="font-weight: 600;" href="#jadwal">Jadwal</a>
                            </li>
                            <li class="nav-item" style="font-family: Red Hat Text, sans-serif;">
                                <a class="nav-link" style="font-weight: 600;" href="#edukasi">Edukasi</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="d-flex">
                    <a href="login" class="auth-btn sign-in me-2">Sign in</a>
                    <a href="login" class="auth-btn sign-up">Sign up</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero bg-success text-white position-relative overflow-hidden">
        <!-- Siluet Logo di Background -->
        <div class="position-absolute start-0 top-50 translate-middle-y opacity-7" style="z-index: 1;">
            <img src="{{ asset('assets/images/logopalsu.png') }}" alt="Siluet Logo" class="img-fluid"
                style="max-width: 300px;">
        </div>

        <div class="container position-relative pt-5" style="z-index: 2;">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-4 fw-bold" style="font-family: 'Red Hat Text'">Buang Sampah <br /> Tanpa Ribet</h1>
                    <p class="lead mt-4" style="font-size: 2rem; font-family: 'Red Hat Text', sans-serif;">#Small Steps, Big Impact</p>
                    <a href="" class="btn btn-warning px-5 py-3 mt-3 start-now-btn">Mulai Sekarang</a>
                </div>
                <div class="col-md-6 text-end">
                    <div class="position-relative">
                        <img src="{{ asset('assets/images/iconpetugas1.png') }}" alt="Petugas Sampah" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pengelolaan Sampah Section -->
    <section class="py-5" id="tentang-kami">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="{{ asset('assets/images/tempatsampah.png') }}" alt="Pengelolaan Sampah" class="img-fluid">
                </div>
                <div class="col-md-6">
                <h2 class="mb-4" style="font-weight: 650; font-family: 'Red Hat Text', sans-serif;">
                    Mengelola Sampah Mudah untuk Kota Lestari
                </h2>

                    <p class="font-redhat">TuNetic membantu masyarakat mengelola sampah dengan mudah dan bertanggung
                        jawab. Setiap langkah kecil bisa berdampak besar bagi lingkungan.</p>

                    <div class="stats-container mt-4">
                        <div class="stats-card">
                            <div class="stat-item">
                                <div class="stat-label">Jumlah Sampah</div>
                                <div class="stat-value" style="font-family: 'Red Hat Text', sans-serif;">1jt Kg+</div>
                            </div>
                            <div class="stats-divider"></div>
                            <div class="stat-item">
                                <div class="stat-label">TPS Aktif</div>
                                <div class="stat-value" style="font-family: 'Red Hat Text', sans-serif;">20</div>
                            </div>
                            <div class="stats-divider"></div>
                            <div class="stat-item">
                                <div class="stat-label">Pengguna</div>
                                <div class="stat-value" style="font-family: 'Red Hat Text', sans-serif;">100</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan Section -->
    <section class="py-5 mb-5 bg-white font-redhat" id="layanan">
        <div class="container">
            <h2 class="fw-semibold mb-2">Layanan</h2>
            <p class="mb-5">Lihat Layanan yang Tersedia di TuNetic</p>

            <div class="row g-5">
                <!-- Jemput Sampah -->
                <div class="col-md-5 text-center">
                    <h3 class="fw-semibold mb-4">Jemput Sampah</h3>
                    <div class="service-image mb-4">
                        <img src="{{ asset('assets/images/truck1.png') }}" alt="Jemput Sampah" class="img-fluid"
                            style="max-height: 200px;">
                    </div>
                    <p class="text-center px-3">
                        Jemput Sampah adalah layanan penjemputan sampah yang telah terjadwal untuk setiap TPS yang ada
                        di Kecamatan Tembalang.
                    </p>
                </div>

                <!-- Garis Pemisah -->
                <div class="col-md-2 d-none d-md-flex justify-content-center">
                    <div class="vertical-divider"></div>
                </div>

                <!-- Lapor Sampah -->
                <div class="col-md-5 text-center">
                    <h3 class="fw-semibold mb-4">Lapor Sampah</h3>
                    <div class="service-image mb-4">
                        <img src="{{ asset('assets/images/petugas2.png') }}" alt="Lapor Sampah" class="img-fluid"
                            style="max-height: 200px;">
                    </div>
                    <p class="text-center px-3">
                        Lapor Sampah memudahkanmu melaporkan sampah liar dengan foto dan lokasi untuk ditindaklanjuti.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- TPS Section -->
    <section id="tps" class="position-relative overflow-hidden">
        <div class="tps-wrapper text-white position-relative z-1">
            <!-- Header -->
            <div class="tps-header text-start">
                <h2 class="mb-2" style="font-family: 'Red Hat Text', sans-serif; font-weight: 600;">TPS</h2>
                <p class="mb-4" style="font-family: 'Red Hat Text', sans-serif;">Temukan Lokasi TPS Terdekat</p>
            </div>

            <!-- TPS Cards -->
            <div class="row g-4">
                <div class="col-md-4 col-sm-6">
                    <div class="tps-card">
                        <img src="{{ asset('assets/images/sampahtps.png') }}" alt="TPS Icon" class="tps-icon">
                        <p class="tps-title">TPS Tulus Harapan</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="tps-card">
                        <img src="{{ asset('assets/images/sampahtps.png') }}" alt="TPS Icon" class="tps-icon">
                        <p class="tps-title">TPS Ketileng Atas</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="tps-card">
                        <img src="{{ asset('assets/images/sampahtps.png') }}" alt="TPS Icon" class="tps-icon">
                        <p class="tps-title">TPS Ketileng Bawah</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="tps-card">
                        <img src="{{ asset('assets/images/sampahtps.png') }}" alt="TPS Icon" class="tps-icon">
                        <p class="tps-title">TPS PSIS</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="tps-card">
                        <img src="{{ asset('assets/images/sampahtps.png') }}" alt="TPS Icon" class="tps-icon">
                        <p class="tps-title">TPS Wanamukti</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="tps-card">
                        <img src="{{ asset('assets/images/sampahtps.png') }}" alt="TPS Icon" class="tps-icon">
                        <p class="tps-title">TPS Perumahan Intan</p>
                    </div>
                </div>
            </div>

            <!-- Lihat Semua -->
            <a href="#" class="lihat-semua">Lihat Semua →</a>
        </div>

        <!-- Jadwal Section -->
        <section class="py-5 position-relative" id="jadwal">
            <div class="container">
                <h2 class="fw-semibold mb-2" style="font-family: 'Red Hat Text', sans-serif;">Jadwal</h2>
                <p class="mb-4" style="font-family: 'Red Hat Text', sans-serif;">Lihat dan Pantau Jadwal Penjemputan Sampah</p>

                <div class="row align-items-center">
                    <div class="col-md-4">
                        <!-- Worker with trash bags image -->
                        <img src="{{ asset('assets/images/petugas1.png') }}" alt="Petugas Sampah" class="img-fluid">
                    </div>
                    <div class="col-md-8">
                        <div class="row g-3">
                            <!-- Senin -->
                            <div class="col-md-6">
                                <div class="card border-0 rounded-4 mb-3"
                                    style="background-color: #299E63; min-height: 70px;">
                                    <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 text-white"
                                            style="font-family: 'Red Hat Text', sans-serif; width: 100px;">Senin</h6>
                                        <div class="text-end" style="width: 150px;">
                                            <small class="text-white" style="font-family: 'Red Hat Text', sans-serif;">
                                                Pagi, 05:00 - 10:00<br>
                                                Sore, 16:00 - 20:00
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Jumat -->
                            <div class="col-md-6">
                                <div class="card border-0 rounded-4 mb-3"
                                    style="background-color: #299E63; min-height: 70px;">
                                    <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 text-white"
                                            style="font-family: 'Red Hat Text', sans-serif; width: 100px;">Jumat</h6>
                                        <div class="text-end" style="width: 150px;">
                                            <small class="text-white" style="font-family: 'Red Hat Text', sans-serif;">
                                                Pagi, 05:00 - 10:00<br>
                                                Sore, 16:00 - 20:00
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Selasa -->
                            <div class="col-md-6">
                                <div class="card border-0 rounded-4 mb-3"
                                    style="background-color: #299E63; min-height: 70px;">
                                    <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 text-white"
                                            style="font-family: 'Red Hat Text', sans-serif; width: 100px;">Selasa</h6>
                                        <div class="text-end" style="width: 150px;">
                                            <small class="text-white" style="font-family: 'Red Hat Text', sans-serif;">
                                                Pagi, 05:00 - 10:00
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sabtu -->
                            <div class="col-md-6">
                                <div class="card border-0 rounded-4 mb-3"
                                    style="background-color: #299E63; min-height: 70px;">
                                    <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 text-white"
                                            style="font-family: 'Red Hat Text', sans-serif; width: 100px;">Sabtu</h6>
                                        <div class="text-end" style="width: 150px;">
                                            <small class="text-white" style="font-family: 'Red Hat Text', sans-serif;">
                                                Pagi, 05:00 - 10:00
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Rabu -->
                            <div class="col-md-6">
                                <div class="card border-0 rounded-4 mb-3"
                                    style="background-color: #299E63; min-height: 70px;">
                                    <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 text-white"
                                            style="font-family: 'Red Hat Text', sans-serif; width: 100px;">Rabu</h6>
                                        <div class="text-end" style="width: 150px;">
                                            <small class="text-white" style="font-family: 'Red Hat Text', sans-serif;">
                                                Pagi, 05:00 - 10:00<br>
                                                Sore, 16:00 - 20:00
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Minggu -->
                            <div class="col-md-6">
                                <div class="card border-0 rounded-4 mb-3"
                                    style="background-color: #299E63; min-height: 70px;">
                                    <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 text-white"
                                            style="font-family: 'Red Hat Text', sans-serif; width: 100px;">Minggu</h6>
                                        <div class="text-end" style="width: 150px;">
                                            <small class="text-white" style="font-family: 'Red Hat Text', sans-serif;">
                                                Pagi, 07:00 - 12:00
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kamis -->
                            <div class="col-md-6">
                                <div class="card border-0 rounded-4 mb-3"
                                    style="background-color: #299E63; min-height: 70px;">
                                    <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 text-white"
                                            style="font-family: 'Red Hat Text', sans-serif; width: 100px;">Kamis</h6>
                                        <div class="text-end" style="width: 150px;">
                                            <small class="text-white" style="font-family: 'Red Hat Text', sans-serif;">
                                                Pagi, 05:00 - 10:00
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gambar Sampah di Kanan Bawah -->
            <div class="position-absolute bottom-0 end-0" style="z-index: 1; max-width: 200px; overflow: hidden;">
                <img src="{{ asset('assets/images/sampah1.png') }}" alt="Sampah" class="img-fluid">
            </div>
        </section>

        <!-- Edukasi Section -->
        <section class="py-5" id="edukasi">
            <div class="container">
                <h2 class="fw-semibold mb-3 lexend-font">Edukasi</h2>
                <p class="mb-4 redhat-fon fw-light">Baca Artikel Tentang Pengelolaan Sampah, Lingkungan, dan Gaya Hidup
                    Berkelanjutan.</p>

                <div class="row">
                    <!-- First Article - Plastic Waste Impact -->
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="row g-0">
                                <div class="col-md-5">
                                    <img src="assets/images/image-6.png" class="img-fluid rounded-start h-100"
                                        alt="Dampak Sampah Plastik" style="object-fit: cover;">
                                </div>
                                <div class="col-md-7">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="far fa-calendar-alt me-2 text-muted"></i>
                                            <small class="text-muted redhat-font">Kamis, 20 Juli 2023</small>
                                        </div>
                                        <h5 class="card-title lexend-font">Dampak Sampah Plastik Terhadap Lingkungan dan
                                            Solusi Pengelolaannya</h5>
                                        <p class="card-text redhat-font">Sampah plastik merusak lingkungan, mencemari
                                            tanah dan laut, serta membahayakan satwa. Solusinya adalah pengurangan
                                            plastik sekali pakai dan pengelolaan sampah yang lebih baik.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Article - Plastic Pollution in Rivers -->
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="row g-0">
                                <div class="col-md-5">
                                    <img src="assets/images/image.png" class="img-fluid rounded-start h-100"
                                        alt="Sampah Plastik di Sungai" style="object-fit: cover;">
                                </div>
                                <div class="col-md-7">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="far fa-calendar-alt me-2 text-muted"></i>
                                            <small class="text-muted redhat-font">Kamis, 28 April 2022</small>
                                        </div>
                                        <h5 class="card-title lexend-font">Sampah Plastik Cemari Sungai di Indonesia
                                        </h5>
                                        <p class="card-text redhat-font">Sampah plastik mencemari sungai di Indonesia,
                                            menyebabkan banjir dan merusak ekosistem. Solusinya: terapkan 3R (Reduce,
                                            Reuse, Recycle).</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Third Article - KLHK Festival -->
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="row g-0">
                                <div class="col-md-5">
                                    <img src="assets/images/imageyo.png" class="img-fluid rounded-start h-100"
                                        alt="Festival LIKE 2" style="object-fit: cover;">
                                </div>
                                <div class="col-md-7">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="far fa-calendar-alt me-2 text-muted"></i>
                                            <small class="text-muted redhat-font">Jumat, 9 Agustus 2024</small>
                                        </div>
                                        <h5 class="card-title lexend-font">KLHK Ajak Masyarakat "Gaya Hidup Minim
                                            Sampah" dalam Festival LIKE 2</h5>
                                        <p class="card-text redhat-font">KLHK menggelar Festival LIKE 2 untuk mendorong
                                            gaya hidup minim sampah dengan prinsip pilah, guna ulang, dan daur ulang
                                            guna mendukung ekonomi sirkular.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="">
            <div class="">
                <div class="row align-items-center">
                    <!-- Kolom untuk Gambar -->
                    <div class="col-12 col-sm-6 col-md-4 text-center text-sm-start mb-4 mb-sm-0">
                        <img src="{{ asset('assets/images/kurakura.png') }}" alt="Turtle" class="img-fluid"
                            style="max-width: 360px;">
                    </div>

                    <!-- Kolom untuk Teks CTA -->
                    <div class="col-12 col-sm-6 col-md-8 px-4">
                        <h2 class="fw-bold text-success mb-3 display-4">
                            Langkah Kecilmu, <span style="color: #24CE78;">Dampak Besar</span> Untuk <span
                                style="color: #24CE78;">Bumi Bersih</span>
                        </h2>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer Section -->
        <footer class="bg-dark text-white py-5 footer-custom" style="background-color: #2E3A40;">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <img src="{{ asset('assets/images/logoputih.png') }}" alt="TuNetic Logo" height="60"
                            class="mb-3">
                        <ul class="list-unstyled d-flex">
                            <li class="me-3"><a href="#" class="text-white"><i class="fab fa-facebook-f fa-2x"></i></a>
                            </li>
                            <li class="me-3"><a href="#" class="text-white"><i class="fab fa-instagram fa-2x"></i></a>
                            </li>
                            <li class="me-3"><a href="#" class="text-white"><i class="fab fa-twitter fa-2x"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-2 mb-4">
                        <h5 class="mb-3 fw-bold">TuNetic</h5>
                        <ul class="list-unstyled">
                            <li class="mb-3"><a href="#" class="text-white text-decoration-none fw-light">Tentang Kami</a></li>
                            <li class="mb-3"><a href="#" class="text-white text-decoration-none fw-light">Layanan</a></li>
                            <li class="mb-3"><a href="#" class="text-white text-decoration-none fw-light">TPS</a></li>
                            <li class="mb-3"><a href="#" class="text-white text-decoration-none fw-light">Jadwal</a></li>
                            <li class="mb-3"><a href="#" class="text-white text-decoration-none fw-light">Edukasi</a></li>
                        </ul>
                    </div>
                    <div class="col-md-2 mb-4">
                        <h5 class="mb-3 fw-semibold">Layanan</h5>
                        <ul class="list-unstyled">
                            <li class="mb-3"><a href="#" class="text-white text-decoration-none fw-light">Jemput Sampah</a></li>
                            <li class="mb-3"><a href="#" class="text-white text-decoration-none fw-light">Lapor Sampah</a></li>
                    </ul>
                    </div>
                    <div class="col-md-4 mb-4">
                        <h5 class="mb-3 fw-bold">Contact</h5>
                        <ul class="list-unstyled">
                            <li class="mb-3"><i class="fas fa-map-marker-alt me-2"></i> <span class="fw-light">Semarang, Indonesia</span>
                            </li>
                            <li class="mb-3"><i class="fas fa-envelope me-2 fw-light"></i> <span class="fw-light">TuNetic@gmail.com</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>