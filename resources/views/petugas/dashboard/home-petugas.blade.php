@extends('components.navbar')

@section('title', 'TuNetic - Solusi Sampah Digital')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Font: Red Hat Text -->
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Text:wght@400;600;700&display=swap" rel="stylesheet">
@endpush

@section('content')
    <!-- Hero Section -->
    <section id="hero" class="hero text-white position-relative overflow-hidden" style="background-color: #299E63;">
        <div class="position-absolute start-0 top-50 translate-middle-y opacity-7" style="z-index: 1;">
            <img src="{{ asset('assets/images/logopalsu.png') }}" alt="Siluet Logo" class="img-fluid"
                style="max-width: 300px;">
        </div>
        <div class="container position-relative pt-5" style="z-index: 2;">
            <div class="row align-items-center">
                <div class="col-md-6" style="padding-left: 40px; padding-top: 100px;">
                    <h1 class="display-4 fw-bold" style="font-family: 'Red Hat Text'; font-size: 4rem;">
                        Buang Sampah <br /> Tanpa Ribet
                    </h1>
                    <p class="lead mt-4" style="font-size: 2rem; font-family: 'Red Hat Text', sans-serif;">#Small Steps,
                        Big Impact</p>
                </div>
                <div class="col-md-6 text-end d-flex justify-content-end align-items-end">
                    <img src="{{ asset('assets/images/petugas/petugas.png') }}" alt="Petugas Sampah" class="img-fluid"
                        style="max-width: 100%; transform: translate(115px, 20px);">
                </div>
            </div>
        </div>
    </section>

    <!-- Schedule Section -->
    <section id="layanan" class="schedule-section py-5" style="margin-top: 100px; margin-bottom: 100px;">
        <div class="container py-5 px-4 px-md-5">
            <div class="row align-items-center gx-5">
                <div class="col-md-4 mb-5 mb-md-0 text-center text-md-start">
                    <img src="{{ asset('assets/images/petugas/trukpetugas.png') }}" alt="Garbage Truck" class="img-fluid"
                        style="max-width: 100%; height: auto;">
                </div>
                <div class="col-md-8" style="padding-left: 8rem;">
                    <h2 class="fw-semibold display-5 mb-4"
                        style="font-family: 'Red Hat Text', sans-serif; font-weight: 700;">Cek Jadwal Hari Ini</h2>
                    <p class="section-description fs-5 mb-5"
                        style="font-family: 'Red Hat Text', sans-serif; font-weight: 300;">
                        Lihat jadwal tugasmu dan tetap terorganisir! Setiap tindakanmu membawa perubahan untuk
                        lingkungan yang lebih bersih. Semangat!
                    </p>
                    <a href="{{ route('petugas.jadwal-pengambilan.index') }}" class="btn btn-success px-5 py-3 fs-5"
                        style="font-family: 'Red Hat Text', sans-serif; font-weight: 500;">Cek Jadwal</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Report Section -->
    <section id="lapor" class="report-section" style="margin-top: 100px; margin-bottom: 100px;">
        <div class="container">
            <h2 class="section-title mb-2" style="font-family: 'Red Hat Text', sans-serif; font-weight: 700;">Lapor
                Sampah</h2>
            <p class="mb-5" style="font-family: 'Red Hat Text', sans-serif;">Lihat daftar laporan sampah yang perlu
                diambil dan segera ditindaklanjuti.</p>

            <!-- First Report Card -->
            <div class="report-card mb-4 p-4 rounded shadow-sm border" style="background-color: #fff;">
                <div class="row">
                    <div class="col-md-3">
                        <img src="{{ asset('assets/images/sampah1.jpg') }}" alt="Sampah di Jalan Banjarsari"
                            class="img-fluid rounded" style="height: 100%; object-fit: cover;">
                    </div>
                    <div class="col-md-9">
                        <h5 class="fw-bold mb-3" style="font-family: 'Red Hat Text', sans-serif;">Sampah Menumpuk di
                            Jalan Banjarsari Selatan</h5>
                        <p class="mb-2"><i class="fas fa-calendar-alt text-success me-2"></i> 20 Maret 2025</p>
                        <p class="mb-2"><i class="fas fa-map-marker-alt text-success me-2"></i> Jl Banjarsari Selatan,
                            No 10, Tembalang, Semarang</p>
                        <p class="mb-2"><i class="fas fa-comment-dots text-success me-2"></i> Ada banyak Sekali Sampah
                            di sini, sangat bau dan mengganggu pejalan kaki. Dimohon petugas untuk cepat menindaklanjuti
                        </p>
                        <p class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> <span
                                class="text-success">Sudah diangkut</span></p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="#" class="btn btn-primary">Terima Tugas</a>
                            <a href="#" class="btn btn-warning text-white">Sedang Proses</a>
                            <a href="#" class="btn btn-success">Selesai</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Report Card -->
            <div class="report-card p-4 rounded shadow-sm border" style="background-color: #fff;">
                <div class="row">
                    <div class="col-md-3">
                        <img src="{{ asset('assets/images/sampah2.jpg') }}" alt="Sampah di Jalan Tembalang"
                            class="img-fluid rounded" style="height: 100%; object-fit: cover;">
                    </div>
                    <div class="col-md-9">
                        <h5 class="fw-bold mb-3" style="font-family: 'Red Hat Text', sans-serif;">Sampah Menumpuk di
                            Jalan Tembalang Raya</h5>
                        <p class="mb-2"><i class="fas fa-calendar-alt text-success me-2"></i> 18 Maret 2025</p>
                        <p class="mb-2"><i class="fas fa-map-marker-alt text-success me-2"></i> Jalan Raya Tembalang No.
                            25, Kelurahan Tembalang, Semarang 50275</p>
                        <p class="mb-2"><i class="fas fa-comment-dots text-success me-2"></i> Tolong ini sampah nya
                            sudah penuh, sangat mengganggu sekali</p>
                        <p class="mb-3"><i class="fas fa-times-circle text-danger me-2"></i> <span class="text-danger">Belum
                                diangkut</span></p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="#" class="btn btn-primary">Terima Tugas</a>
                            <a href="#" class="btn btn-warning text-white">Sedang Proses</a>
                            <a href="#" class="btn btn-success">Selesai</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 footer-custom" style="background-color: #2E3A40;">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <img src="{{ asset('assets/images/logoputih.png') }}" alt="TuNetic Logo" height="60" class="mb-3">
                    <ul class="list-unstyled d-flex">
                        <li class="me-3"><a href="#" class="text-white"><i class="fab fa-facebook-f fa-2x"></i></a></li>
                        <li class="me-3"><a href="#" class="text-white"><i class="fab fa-instagram fa-2x"></i></a></li>
                        <li class="me-3"><a href="#" class="text-white"><i class="fab fa-twitter fa-2x"></i></a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="mb-3 fw-bold">TuNetic</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3"><a href="#" class="text-white text-decoration-none fw-light">Tentang Kami</a>
                        </li>
                        <li class="mb-3"><a href="#" class="text-white text-decoration-none fw-light">Layanan</a></li>
                        <li class="mb-3"><a href="#" class="text-white text-decoration-none fw-light">TPS</a></li>
                        <li class="mb-3"><a href="#" class="text-white text-decoration-none fw-light">Jadwal</a></li>
                        <li class="mb-3"><a href="#" class="text-white text-decoration-none fw-light">Edukasi</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="mb-3 fw-semibold">Layanan</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3"><a href="#" class="text-white text-decoration-none fw-light">Jemput Sampah</a>
                        </li>
                        <li class="mb-3"><a href="#" class="text-white text-decoration-none fw-light">Lapor Sampah</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3 fw-bold">Contact</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3"><i class="fas fa-map-marker-alt me-2"></i> <span class="fw-light">Semarang,
                                Indonesia</span></li>
                        <li class="mb-3"><i class="fas fa-envelope me-2 fw-light"></i> <span
                                class="fw-light">TuNetic@gmail.com</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
@endpush