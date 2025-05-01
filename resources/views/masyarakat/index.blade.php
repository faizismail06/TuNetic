@extends('components.navbar')


@section('content')
    {{-- Hero Section --}}

    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Text:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* 1. Hapus margin/padding default dari HTML dan body */
        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            overflow-x: hidden;
        }

        /* 2. Atasi flex container yang bisa menyebabkan overflow */
        section>div {
            max-width: 100%;
            overflow-x: hidden;
        }

        /* 3. Pastikan gambar tidak melebihi parent */
        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        h1,
        h2,
        p {
            font-family: 'Red Hat Text', sans-serif;
        }
    </style>


    <section style="background-color: #299E63; color: white; position: relative; height: 100vh; overflow: hidden;">
        <!-- Logo di belakang teks -->
        <img src="{{ asset('assets/images/Masyarakat/aksenlogo.png') }}" alt="Logo Belakang"
            style="position: absolute; top: 10%; left: 0px; width: 300px; z-index: 0;">

        <!-- Teks utama -->
        <div style="position: absolute; top: 50%; left: 120px; transform: translateY(-50%); z-index: 1;">
            <h1 style="font-family: 'Red Hat Text', sans-serif; font-size: 5rem; font-weight: 700; margin: 0;">Selamat
                Datang<br>di TuNetic</h1>
            <p style="font-family: 'Red Hat Text', sans-serif;font-size: 1.4rem; margin-top: 0;">#Small Steps, Big Impact
            </p>
        </div>

        <!-- Gambar karakter -->
        <img src="{{ asset('assets/images/Masyarakat/iconpetugas2.png') }}" alt="Petugas TuNetic"
            style="position: absolute; bottom: 0; right: 0; max-width: 47%; height: auto; margin: 0; padding: 0; z-index: 2;">
    </section>


    {{-- Lapor Sampah --}}
    <section style="display: flex; align-items: center; justify-content: center; padding: 150px 20px;gap: 40px;">
        <!-- Gambar -->
        <div style="max-width: 400px; text-align: center; margin-right: 100px; max-width: 600px;">
            <img src="{{ asset('assets/images/Masyarakat/sampah2.png') }}" alt="Ilustrasi Sampah"
                style="width: 800px; height: auto;">

        </div>

        <!-- Konten Teks -->
        <div style="max-width: 550px;">
            <h1 style="font-family: 'Red Hat Text', sans-serif; font-size: 2.5rem; margin-bottom: 40px; font-weight: 600">
                Lapor Sampah</h1>
            <p style="font-family: 'Red Hat Text', sans-serif; font-size: 1.5rem; margin-bottom: 40px; color: #555;">
                Laporkan melaporkan lokasi sampah yang perlu dibersihkan dengan mengunggah foto, menambahkan deskripsi,
                dan penandaan di peta untuk tindakan lanjut.
            </p>
            <a href="/masyarakat/lapor"
                style="font-family: 'Red Hat Text', sans-serif; font-size: 1.1rem; background-color: #299E63; color: white; padding: 15px 25px; text-decoration: none; border-radius: 8px; display: inline-block;">
                Laporkan Sampah
            </a>
        </div>
    </section>



    {{-- Riwayat Pengajuan --}}
    <div class="container mt-5">
        <div class="row align-items-center">
            <!-- Kiri: Ilustrasi dan Teks -->
            <div class="col-md-6 mb-4">
                <h2 class="fw-bold" style="font-family: 'Red Hat Display', sans-serif;">Riwayat Pengajuan</h2>
                <p class="text-muted" style="font-family: 'Red Hat Text', sans-serif; margin-bottom: 55px;">Lihat Status dan
                    Perkembangan Laporan
                    Sampah Anda</p>
                <img src="{{ asset('assets/images/Masyarakat/petugas3.png') }}" alt="Ilustrasi" class="img-fluid mt-3"
                    style="max-width: 550px; margin-top: 40px;">
            </div>

            <!-- Kanan: Daftar Laporan -->
            <div class="col-md-6">
                @if($laporanTerbaru->isEmpty())
                    <p>Belum ada laporan yang dikirim.</p>
                @else
                    <div class="d-flex flex-column gap-4" style="padding-top: px;">
                        @foreach($laporanTerbaru as $laporan)
                            <div class="card border-0 shadow-sm p-3" style="border-radius: 16px;">
                                <div class="d-flex align-items-center gap-3">
                                    <!-- Gambar -->
                                    <div style="flex: 0 0 150px;">
                                        @if($laporan->gambar)
                                            <img src="{{ asset('storage/' . $laporan->gambar) }}" class="img-fluid rounded"
                                                style="width: 150px; height: 120px; object-fit: cover;" alt="Gambar Laporan">
                                        @else
                                            <img src="{{ asset('images/default.jpg') }}" class="img-fluid rounded"
                                                style="width: 150px; height: 120px; object-fit: cover;" alt="Tidak Ada Gambar">
                                        @endif
                                    </div>

                                    <!-- Info Laporan -->
                                    <div style="flex: 1;">
                                        <h6 class="fw-bold mb-2" style="font-family: 'Red Hat Text', sans-serif;">
                                            {{ $laporan->judul }}
                                        </h6>

                                        <div class="d-flex align-items-center mb-1 text-muted" style="font-size: 0.9rem;">
                                            <i class="fas fa-calendar-alt me-2 text-success"></i>
                                            {{  $laporan->created_at->format('d F Y') }}
                                        </div>

                                        <div class="d-flex align-items-center mb-1 text-muted" style="font-size: 0.9rem;">
                                            <i class="fas fa-map-marker-alt me-2 text-success"></i>
                                            {{ $laporan->lokasi ?? 'Lokasi tidak tersedia' }}
                                        </div>


                                        <div class="d-flex align-items-center" style="font-size: 0.9rem;">
                                            @if($laporan->status == 0)
                                                <i class="fas fa-exclamation-circle me-2 text-danger"></i>
                                                <span class="text-danger">Belum diangkut</span>
                                            @elseif($laporan->status == 1)
                                                <i class="fas fa-hourglass-half me-2 text-warning"></i>
                                                <span class="text-warning">Diproses</span>
                                            @else
                                                <i class="fas fa-check-circle me-2 text-success"></i>
                                                <span class="text-success">Sudah diangkut</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Tombol Lihat Semua -->
                    <div class="text-end mt-4">
                        <a href="{{ route('lapor.riwayat') }}" class="text-success"
                            style="text-decoration: none; font-family: 'Red Hat Text', sans-serif; font-weight: 500;">
                            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>





    {{-- Lacak Armada --}}
    <section class="py-5" style="font-family: 'Red Hat Text', sans-serif; margin-top: 80px;">
        <div style="flex: 1; padding-left: 100px; font-family: 'Red Hat Text', sans-serif;">
            <h2 style="font-size: 2.5rem; margin-bottom: 10px; font-weight: 600;">Lacak Armada</h2>
            <p style="color: #555; margin-bottom: 30px;">Cek Rute Armada dengan Mudah</p>

            <!-- Gambar Armada -->
            <img src="{{ asset('assets/images/Masyarakat/maps-armada-placeholder.png') }}" alt="Peta Placeholder"
                style="width: 1320px; height: 400px;
                                                                                        margin-top: 30px;
                                                                                        border-radius: 12px;
                                                                                        box-shadow: 0 4px 12px rgba(0,0,0,0.1);">

            <!-- Tombol kanan bawah -->
            <div style="display: flex; justify-content: flex-end; margin-top: 20px; padding-right: 100px;">
                <a href="{{ route('masyarakat.lacak') }}" style="font-size: 1.1rem;
                                                                                                      background-color: #299E63;
                                                                                                      margin-top: 20px;
                                                                                                      color: white;
                                                                                                      padding: 15px 25px;
                                                                                                      text-decoration: none;
                                                                                                      border-radius: 8px;">
                    Cek Rute Armada
                </a>
            </div>
        </div>
    </section>
@endsection
