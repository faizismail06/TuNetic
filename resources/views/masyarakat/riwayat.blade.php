@extends('components.navbar')

@section('content')
    <!-- Font Red Hat Text -->
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Text:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <div class="main-wrapper">
        <div class="riwayat-container">
            <h2>Riwayat Pengajuan</h2>
            <p>Lihat Status dan Perkembangan Laporan Sampah Anda</p>

            @foreach($laporan as $lapor)
                <div class="laporan-card">
                    <img src="{{ $lapor->gambar }}" alt="Gambar Sampah">
                    <div class="laporan-detail">
                        <div class="laporan-title">{{ $lapor->judul }}</div>
                        <div class="laporan-meta">
                            <div><i class="fas fa-calendar-days icon"></i>
                                {{ \Carbon\Carbon::parse($lapor->created_at)->translatedFormat('d F Y') }}</div>
                            <div>
                                <i class="fas fa-location-dot icon"></i>
                                {{ $lapor->lokasi ?? 'Lokasi tidak tersedia' }}
                            </div>

                        </div>

                        @if($lapor->status == 0)
                            <span class="status belum">
                                <i class="fas fa-circle-exclamation icon-status"></i> Belum diangkut
                            </span>
                        @elseif($lapor->status == 1)
                            <span class="status sedang">
                                <i class="fas fa-clock icon-status"></i> Sedang proses
                            </span>
                        @elseif($lapor->status == 2)
                            <span class="status belum">
                                <i class="fas fa-circle-xmark fa-1.5x icon-status"></i> Ditolak
                            </span>
                        @elseif($lapor->status == 3)
                            <span class="status selesai">
                                <i class="fas fa-check-circle text-success icon-status"></i> Sudah diangkut
                            </span>
                        @endif

                        <div class="laporan-action d-flex justify-content-end">
                            <a href="{{ route('laporan.show', $lapor->id) }}" class="btn-detail">Lihat Detail</a>
                        </div>

                    </div>
                </div>

            @endforeach

            <a href="{{ route('masyarakat.lapor') }}" class="btn-lapor-baru">Buat Laporan Baru</a>
        </div>
    </div>

    <style>
        body {
            font-family: 'Red Hat Text', sans-serif;
        }

        .main-wrapper {
            padding: 70px 20px;
        }

        .riwayat-container {
            max-width: 900px;
            margin: auto;
        }

        .riwayat-container h2 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 10px;

        }

        .riwayat-container p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 50px;

        }


        .laporan-card {
            display: flex;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            gap: 25px;
            min-height: 160px;
            background-color: #fff;
            align-items: center;

        }

        .laporan-card img {
            width: 200px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .laporan-detail {
            display: flex;
            flex-direction: column;
            justify-content: center;
            font-size: 1.05rem;
            color: #555;
            flex: 1;
        }

        .laporan-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .laporan-meta>div {
            margin-bottom: 6px;
        }


        .icon {
            color: #299E63;
            margin-right: 8px;
        }

        .icon-status {
            margin-right: 7px;
        }

        .status {
            font-weight: 400;
            display: inline-block;
            font-family: 'Red Hat Text', sans-serif;
        }

        .status.belum {
            color: #dc3545;
        }

        .status.sedang {
            color: #FFB800;
        }

        .status.selesai {
            color: #299E63;
        }

        .btn-lapor-baru {
            display: inline-block;
            background-color: #299E63;
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 30px;
            font-size: 1.1rem;
        }

        .btn-detail {
            margin-left: auto;
            margin-top: 12px;
            display: inline-block;
            padding: 10px 18px;
            /* background-color: #f0f0f0; */
            color: #299E63;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.3s;
            display: flex;
            justify-content: flex-end;
            /* Menggeser tombol ke kanan */

        }

        .laporan-action {
            display: flex;
            justify-content: flex-end;
            /* Memastikan tombol berada di sebelah kanan */
            width: 100%;
            /* Memanfaatkan lebar penuh */
            margin-top: 12px;/
        }

        /* .btn-detail:hover {
                                                                background-color: #e0e0e0;
                                                            } */
    </style>

@endsection