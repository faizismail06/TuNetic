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
                    <div class="laporan-image">
                        <img src="{{ $lapor->gambar }}" alt="Gambar Sampah">
                    </div>
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

                        <div class="status-wrapper">
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
                        </div>

                        <div class="laporan-action">
                            <a href="{{ route('masyarakat.detailRiwayat', $lapor->id) }}" class="btn-detail">Lihat Detail</a>
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
            text-align: left;
        }

        .riwayat-container p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 50px;
            text-align: left;
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .laporan-image {
            flex-shrink: 0;
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
            min-width: 0;
        }

        .laporan-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .laporan-meta>div {
            margin-bottom: 6px;
        }

        .status-wrapper {
            margin: 12px 0;
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
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .status.belum {
            color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }

        .status.sedang {
            color: #ff8c00;
            background: rgba(255, 140, 0, 0.1);
        }

        .status.selesai {
            color: #299E63;
            background: rgba(41, 158, 99, 0.1);
        }

        .btn-lapor-baru {
            display: block;
            background-color: #299E63;
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 30px;
            font-size: 1.1rem;
            width: fit-content;
            /* Lebar otomatis mengikuti isi */
            margin-left: auto;
            /* Dorong ke kanan */
            box-sizing: border-box;
        }

        .btn-detail {
            margin-top: 12px;
            display: inline-block;
            padding: 10px 18px;
            color: #299E63;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.3s;
            /* border: 1px solid #299E63; */
            background-color: transparent;
        }

        .btn-detail:hover {
            background-color: #299E63;
            color: white;
        }

        .laporan-action {
            display: flex;
            justify-content: flex-end;
            width: 100%;
            margin-top: 12px;
        }

        /* Media Queries untuk Responsivitas */

        /* Tablet - Medium screens */
        @media (max-width: 768px) {
            .main-wrapper {
                padding: 50px 15px;
            }

            .riwayat-container h2 {
                font-size: 1.7rem;
            }

            .riwayat-container p {
                font-size: 1rem;
                margin-bottom: 30px;
            }

            .laporan-card {
                padding: 20px;
                gap: 20px;
                min-height: auto;
            }

            .laporan-card img {
                width: 150px;
                height: 120px;
            }

            .laporan-title {
                font-size: 1.1rem;
            }

            .laporan-detail {
                font-size: 1rem;
            }
        }

        /* Mobile - Small screens */
        @media (max-width: 576px) {
            .main-wrapper {
                padding: 40px 10px;
            }

            .riwayat-container h2 {
                font-size: 1.5rem;
            }

            .riwayat-container p {
                font-size: 0.9rem;
                margin-bottom: 25px;
                padding: 0;
                text-align: left;
            }

            .laporan-card {
                flex-direction: column;
                padding: 15px;
                gap: 15px;
                text-align: center;
            }

            .laporan-image {
                width: 100%;
                display: flex;
                justify-content: center;
            }

            .laporan-card img {
                width: 100%;
                max-width: 250px;
                height: 180px;
            }

            .laporan-detail {
                text-align: left;
                width: 100%;
            }

            .laporan-title {
                font-size: 1.1rem;
                text-align: center;
                margin-bottom: 15px;
            }

            .laporan-meta {
                margin-bottom: 15px;
            }

            .laporan-meta>div {
                margin-bottom: 8px;
                word-wrap: break-word;
            }

            .status-wrapper {
                text-align: center;
                margin: 15px 0;
            }

            .laporan-action {
                justify-content: center;
                margin-top: 15px;
            }

            .btn-detail {
                padding: 12px 20px;
                font-size: 0.95rem;
            }

            .btn-lapor-baru {
                padding: 15px 20px;
                font-size: 1rem;
                margin-top: 25px;
            }
        }

        /* Extra small screens */
        @media (max-width: 375px) {
            .main-wrapper {
                padding: 30px 8px;
            }

            .riwayat-container h2 {
                font-size: 1.3rem;
            }

            .laporan-card {
                padding: 12px;
                margin-bottom: 20px;
            }

            .laporan-meta>div {
                font-size: 0.9rem;
            }

            .icon {
                margin-right: 6px;
            }

            .status {
                font-size: 0.9rem;
            }

            .btn-detail {
                padding: 10px 16px;
                font-size: 0.9rem;
            }
        }
    </style>

@endsection
