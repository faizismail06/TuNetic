@extends('components.navbar')

@section('title', 'TuNetic - Solusi Sampah Digital')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Font: Red Hat Text -->
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Text:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom responsive styles */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem !important;
                padding-left: 20px !important;
                padding-top: 50px !important;
            }
            .hero p {
                font-size: 1.2rem !important;
                padding-left: 20px !important;
            }
            .hero .col-md-6:first-child {
                padding-left: 20px !important;
                padding-top: 50px !important;
            }
            .hero .position-absolute img {
                max-width: 200px !important;
            }
            .hero .col-md-6:last-child {
                height: 50vh !important;
            }
            .hero .col-md-6:last-child img {
                height: 70% !important;
                right: -20px !important;
            }
            .schedule-section .col-md-8 {
                padding-left: 1rem !important;
            }
            .schedule-section h2 {
                font-size: 2rem !important;
            }
            .schedule-section p {
                font-size: 1rem !important;
            }
            .report-card .col-md-3 img {
                height: 200px !important;
                margin-bottom: 15px;
            }
            .report-card .row {
                flex-direction: column;
            }
            .report-card .col-md-3, .report-card .col-md-9 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
        @media (max-width: 576px) {
            .hero h1 {
                font-size: 2rem !important;
            }
            .hero p {
                font-size: 1rem !important;
            }
            .schedule-section {
                margin-top: 50px !important;
                margin-bottom: 50px !important;
            }
            .report-section {
                margin-top: 50px !important;
                margin-bottom: 50px !important;
            }
            .btn {
                font-size: 0.9rem !important;
                padding: 10px 20px !important;
            }
        }
        @media (min-width: 992px) and (max-width: 1199px) {
            .hero h1 {
                font-size: 3.5rem !important;
            }
            .hero .col-md-6:first-child {
                padding-left: 60px !important;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section -->
<section id="hero" class="hero text-white position-relative overflow-hidden" style="background-color: #299E63; min-height: 100vh;">
    <div class="position-absolute start-0 top-50 translate-middle-y opacity-25 d-none d-md-block" style="z-index: 1;">
        <img src="{{ asset('assets/images/logopalsu.png') }}" alt="Siluet Logo" class="img-fluid"
            style="max-width: 400px;">
    </div>
    <div class="container-fluid position-relative h-100" style="z-index: 2;">
        <div class="row align-items-center h-100">
            <div class="col-md-6 col-12" style="padding-left: 80px; padding-top: 120px;">
                <h1 class="fw-bold mb-4" style="font-family: 'Red Hat Text', sans-serif; font-size: 4.5rem; line-height: 1.1; font-weight: 800;">
                    Siap Bertugas<br />Hari ini?
                </h1>
                <p class="mt-4" style="font-size: 1.5rem; font-family: 'Red Hat Text', sans-serif; font-weight: 400; opacity: 0.9;">
                    #Small Steps, Big Impact
                </p>
            </div>
            <div class="col-md-6 col-12 position-relative" style="height: 100vh;">
                <div class="position-absolute" style="right: -50px; bottom: 0; width: 100%; height: 100%;">
                    <img src="{{ asset('assets/images/petugas/petugas.png') }}" alt="Petugas Sampah" class="img-fluid"
                        style="position: absolute; right: 0; bottom: 0; height: 85%; width: auto; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>
</section>

  <!-- Schedule Section -->
    <section id="layanan" class="schedule-section py-5" style="margin-top: 100px; margin-bottom: 100px;">
        <div class="container py-5 px-4 px-md-5">
            <div class="row align-items-center gx-5">
                <div class="col-md-4 col-12 mb-5 mb-md-0 text-center text-md-start">
                    <img src="{{ asset('assets/images/petugas/trukpetugas.png') }}" alt="Garbage Truck" class="img-fluid"
                        style="max-width: 100%; height: auto;">
                </div>
                <div class="col-md-8 col-12" style="padding-left: 8rem;">
                    <h2 class="fw-semibold display-5 mb-4"
                        style="font-family: 'Red Hat Text', sans-serif; font-weight: 700;">Cek Jadwal Hari Ini</h2>
                    <p class="section-description fs-5 mb-5"
                        style="font-family: 'Red Hat Text', sans-serif; font-weight: 300;">
                        Lihat jadwal tugasmu dan tetap terorganisir! Setiap tindakanmu membawa perubahan untuk
                        lingkungan yang lebih bersih. Semangat!
                    </p>
                    <a href="{{ route('petugas.jadwal-pengambilan.index') }}" class="btn px-5 py-3 fs-5"
                        style="font-family: 'Red Hat Text', sans-serif; font-weight: 500; background-color: #299e63; color: white; border: none;">Cek Jadwal</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Report Section -->
    <section id="lapor" class="report-section" style="margin-top: 100px; margin-bottom: 100px;">
        <div class="container">
            <h2 class="section-title mb-2" style="font-family: 'Red Hat Text', sans-serif; font-weight: 700;">Lapor Sampah</h2>
            <p class="mb-5" style="font-family: 'Red Hat Text', sans-serif;">
                Lihat daftar laporan sampah yang perlu diambil dan segera ditindaklanjuti.
            </p>

            @if($laporSampah->count() > 0)
                @foreach($laporSampah as $laporan)
                    <div class="report-card mb-4 p-4 rounded shadow-sm border bg-white">
                        <div class="row">
                            <div class="col-md-3 col-12 mb-3 mb-md-0">
                                @if($laporan->gambar)
                                    <img src="{{ asset('storage/' . $laporan->gambar) }}" alt="Foto Sampah"
                                        class="img-fluid rounded" style="height: 150px; width: 100%; object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light rounded"
                                        style="height: 150px; width: 100%;">
                                        <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-9 col-12">
                                <h5 class="fw-bold mb-3" style="font-family: 'Red Hat Text', sans-serif;">
                                    {{ $laporan->judul }}
                                </h5>

                                <div class="mb-2">
                                    <span class="badge rounded-pill px-3 py-2" style="background-color: #e8f5e8; color: #299e63; font-size: 0.9rem;">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $laporan->created_at ? $laporan->created_at->format('d M Y') : 'Tanggal tidak tersedia' }}
                                    </span>
                                </div>

                                <div class="mb-2">
                                    <span style="color: #299e63; font-weight: 500;">
                                        <i class="fas fa-map-marker-alt me-2"></i> {{ $laporan->alamat }}
                                    </span>
                                </div>

                                <div class="mb-3">
                                    <span style="color: #666; font-size: 0.95rem;">
                                        <i class="fas fa-comment-dots me-2" style="color: #299e63;"></i>
                                        {{ $laporan->deskripsi }}
                                    </span>
                                </div>

                                <div class="mb-3">
                                    @if($laporan->status == 1)
                                        <span style="color: #299e63; font-weight: 500;">
                                            <i class="fas fa-check-circle me-2"></i> Sudah diangkut
                                        </span>
                                        @if($laporan->bukti_foto)
                                            <div class="mt-2">
                                                <a href="{{ asset('storage/' . $laporan->bukti_foto) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-eye me-1"></i> Lihat Bukti
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <span style="color: #dc3545; font-weight: 500;">
                                            <i class="fas fa-exclamation-circle me-2"></i> Belum diangkut
                                        </span>
                                    @endif
                                </div>

                                @if($laporan->status == 0)
                                <button type="button" class="btn px-4 py-2" style="background-color: #ffb800; color: #fff; border: none; font-weight: 500; border-radius: 8px;"
                                        data-bs-toggle="modal" data-bs-target="#buktiModal{{ $laporan->id }}">
                                    Kirim Bukti
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Modal Bukti Kirim, optional: letakkan modal di luar loop jika tidak ingin banyak modal -->
                    <div class="modal fade" id="buktiModal{{ $laporan->id }}" tabindex="-1" aria-labelledby="buktiModalLabel{{ $laporan->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('lapor-sampah.kirim-bukti', $laporan->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="buktiModalLabel{{ $laporan->id }}">Kirim Bukti Pengangkutan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="bukti_foto{{ $laporan->id }}" class="form-label">Upload Bukti Foto</label>
                                            <input type="file" class="form-control" id="bukti_foto{{ $laporan->id }}" name="bukti_foto" accept="image/*" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-success">Kirim</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info">
                    Belum ada laporan sampah.
                </div>
            @endif
        </div>
    </section>


@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
@endpush