<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TuNetic - Solusi Sampah Digital</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
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
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item" style="font-family: Red Hat Text, sans-serif; font-weight: bolder;">
                            <a class="nav-link" style="font-weight: 600;" href="#tentang-kami">Home</a>
                        </li>
                        <li class="nav-item" style="font-family: Red Hat Text, sans-serif;">
                            <a class="nav-link" style="font-weight: 600;" href="#layanan">Jadwal Pengambilan</a>
                        </li>
                        <li class="nav-item" style="font-family: Red Hat Text, sans-serif;">
                            <a class="nav-link" style="font-weight: 600;" href="#tps">Lapor Sampah</a>
                        </li>
                    </ul>
                    @auth
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ asset('storage/profile/' . auth()->user()->profile_photo) }}" alt="Profile"
                                        class="rounded-circle"
                                        style="width: 30px; height: 30px; object-fit: cover; margin-right: 8px;">
                                    {{ auth()->user()->name }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="/profile">Profile</a></li>
                                    <li><a class="dropdown-item" href="/logout">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    @endauth

                    @guest
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="/login">Login</a>
                            </li>
                        </ul>
                    @endguest
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
                <!-- Teks Kiri -->
                <div class="col-md-6" style="padding-left: 40px; padding-top: 100px;">
                    <h1 class="display-4 fw-bold" style="font-family: 'Red Hat Text'; font-size: 4rem;">
                        Buang Sampah <br /> Tanpa Ribet
                    </h1>

                    <p class="lead mt-4" style="font-size: 2rem; font-family: 'Red Hat Text', sans-serif;">#Small Steps,
                        Big Impact</p>
                    <a href="" class="btn btn-warning px-5 py-3 mt-3 start-now-btn">Mulai Sekarang</a>
                </div>

                <!-- Gambar Kanan -->
                <div class="col-md-6 text-end d-flex justify-content-end align-items-end">
                    <img src="{{ asset('assets/images/petugas/petugas.png') }}" alt="Petugas Sampah" class="img-fluid"
                        style="max-width: 100%; transform: translate(115px, 20px);">
                </div>
            </div>
        </div>
    </section>

<!-- Schedule Section -->
<section class="schedule-section py-5 py-md-6">
    <div class="container py-5 px-4 px-md-5">
        <div class="row align-items-center">
            <div class="col-md-5 mb-5 mb-md-0 text-center text-md-start">
                <img src="{{ asset('assets/images/petugas/trukpetugas.png') }}" alt="Garbage Truck" class="img-fluid" style="max-width: 100%; height: auto;">
            </div>
            <div class="col-md-7 ps-md-5" style="padding-left: 4rem;">
                <h2 class="fw-bold display-5 mb-4">Cek Jadwal Hari Ini</h2>
                <p class="section-description fs-5 mb-5">
                    Lihat jadwal tugasmu dan tetap terorganisir! Setiap tindakanmu membawa perubahan untuk
                    lingkungan yang lebih bersih. Semangat!
                </p>
                <a href="#" class="btn btn-success px-5 py-3 fs-5">Cek Jadwal</a>
            </div>
        </div>
    </div>
</section>




    <!-- Report Section -->
    <section class="report-section">
        <div class="container">
            <h2 class="section-title">Lapor Sampah</h2>
            <p class="mb-4">Lihat daftar laporan sampah yang perlu diambil dan segera ditindaklanjuti.</p>

            <!-- First Report Card -->
            <div class="report-card">
                <div class="row">
                    <div class="col-md-3">
                        <img src="sampah1.jpg" alt="Sampah di Jalan Banjarsari" class="img-fluid rounded">
                    </div>
                    <div class="col-md-9">
                        <h3 class="report-title">Sampah Menumpuk di Jalan Banjarsari Selatan</h3>
                        <div class="report-meta">
                            <div class="report-date">
                                <i class="fas fa-calendar-alt text-success"></i>
                                <span>20 Maret 2025</span>
                            </div>
                            <div class="report-location">
                                <i class="fas fa-map-marker-alt text-success"></i>
                                <span>Jl Banjarsari Selatan, No 10, Tembalang, Semarang</span>
                            </div>
                            <div class="report-description">
                                <i class="fas fa-info-circle text-success"></i>
                                <span>Ada banyak Sekali Sampah di sini, sangat bau dan mengganggu pejalan kaki. Dimohon
                                    petugas untuk cepat menindaklanjuti</span>
                            </div>
                            <div class="report-status">
                                <i class="fas fa-check-circle text-success"></i>
                                <span>Sudah diangkut</span>
                            </div>
                        </div>
                        <div class="report-actions">
                            <a href="#" class="btn btn-primary">Terima Tugas</a>
                            <a href="#" class="btn btn-warning">Sedang Proses</a>
                            <a href="#" class="btn btn-success">Selesai</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Report Card -->
            <div class="report-card">
                <div class="row">
                    <div class="col-md-3">
                        <img src="sampah2.jpg" alt="Sampah di Jalan Tembalang" class="img-fluid rounded">
                    </div>
                    <div class="col-md-9">
                        <h3 class="report-title">Sampah Menumpuk di Jalan Tembalang Raya</h3>
                        <div class="report-meta">
                            <div class="report-date">
                                <i class="fas fa-calendar-alt text-success"></i>
                                <span>18 Maret 2025</span>
                            </div>
                            <div class="report-location">
                                <i class="fas fa-map-marker-alt text-success"></i>
                                <span>Jalan Raya Tembalang No. 25, Kelurahan Tembalang, Semarang 50275</span>
                            </div>
                            <div class="report-description">
                                <i class="fas fa-info-circle text-success"></i>
                                <span>Tolong ini sampah nya sudah penuh, sangat mengganggu sekali</span>
                            </div>
                            <div class="report-status">
                                <i class="fas fa-times-circle text-danger"></i>
                                <span>Belum diangkut</span>
                            </div>
                        </div>
                        <div class="report-actions">
                            <a href="#" class="btn btn-primary">Terima Tugas</a>
                            <a href="#" class="btn btn-warning">Sedang Proses</a>
                            <a href="#" class="btn btn-success">Selesai</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <a href="#" class="btn btn-link text-success">Lihat Semua <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <!-- Footer
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <img src="logo-white.png" alt="TuNetic Logo" height="60" class="mb-3">
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div class="col-md-3">
                    <h5>TuNetic</h5>
                    <ul class="footer-links">
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Layanan</a></li>
                        <li><a href="#">Tips</a></li>
                        <li><a href="#">Jadwal</a></li>
                        <li><a href="#">Edukasi</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Layanan</h5>
                    <ul class="footer-links">
                        <li><a href="#">Jemput Sampah</a></li>
                        <li><a href="#">Lapor Sampah</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact</h5>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt"></i> Semarang, Indonesia</li>
                        <li><i class="fas fa-envelope"></i> TuNetic@gmail.com</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html> -->
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