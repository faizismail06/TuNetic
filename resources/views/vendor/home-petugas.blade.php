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
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="logo.png" alt="TuNetic Logo" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Jadwal Pengambilan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Lapor Sampah</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="me-2">Tami</span>
                    <div class="profile-pic">
                        <img src="profile.jpg" alt="Profile Picture" class="rounded-circle" width="32" height="32">
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="hero-title">Siap Bertugas<br>Hari ini?</h1>
                    <p class="hero-tagline">#Small Steps, Big Impact</p>
                </div>
                <div class="col-md-6 text-center">
                    <div class="hero-image-container">
                        <img src="hero-worker.png" alt="Waste Collector" class="hero-image">
                        <div class="sdg-logo">
                            <img src="sdg-logo.png" alt="Sustainable Development Goals" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Schedule Section -->
    <section class="schedule-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4">
                   <img src="assets/petugas/trukpetugas.jpg" alt="Garbage Truck" class="img-fluid">
                </div>
                <div class="col-md-8">
                    <h2 class="section-title">Cek Jadwal Hari Ini</h2>
                    <p class="section-description">
                        Lihat jadwal tugasmu dan tetap terorganisir! Setiap tindakanmu membawa perubahan untuk lingkungan yang lebih bersih. Semangat!
                    </p>
                    <a href="#" class="btn btn-primary">Cek Jadwal</a>
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
                                <span>Ada banyak Sekali Sampah di sini, sangat bau dan mengganggu pejalan kaki. Dimohon petugas untuk cepat menindaklanjuti</span>
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

    <!-- Footer -->
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
</html>