@extends('components.navbar')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Text:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <div class="detail-container">
        <h2>Detail Laporan</h2>
        <p>Ikuti perkembangan Status Laporan Anda yang Tertera di Bawah ini</p>

        <div class="detail-card">
            <img src="{{ $laporan->gambar }}" alt="Gambar Sampah">
            <div class="detail-info">
                <h3>{{ $laporan->judul }}</h3>
                <div><i class="fas fa-calendar-days icon"></i>
                    {{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('d F Y') }}
                </div>
                <div>
                    <i class="fas fa-location-dot icon"></i>
                    {{ $laporan->lokasi ?? 'Lokasi tidak tersedia' }}
                </div>
                <div>
                    <i class="fas fa-comment icon"></i> {{ $laporan->deskripsi ?? '-' }}
                </div>

                @if($laporan->status == 0)
                    <div class="status-item belum">
                        <i class="fas fa-circle-exclamation"></i> Belum diangkut
                    </div>
                @elseif($laporan->status == 1)
                    <div class="status-item sedang">
                        <i class="fas fa-clock"></i> Sedang proses
                    </div>
                @elseif($laporan->status == 2)
                    <div class="status-item belum">
                        <i class="fas fa-circle-xmark"></i> Ditolak
                    </div>
                @elseif($laporan->status == 3)
                    <div class="status-item selesai">
                        <i class="fas fa-check-circle"></i> Sudah diangkut
                    </div>
                @endif

                @if($laporan->status == 3 && !empty($laporan->tanggal_diangkut))
                    <p><strong>Diangkut pada:</strong>
                        {{ \Carbon\Carbon::parse($laporan->tanggal_diangkut)->translatedFormat('d F Y, H:i') }}
                    </p>
                @endif
            </div>
        </div>

        <style>
            h2 {
                margin-top: 30px;
                margin-bottom: 30px;
            }

            .detail-container {
                max-width: 1000px;
                margin: auto;
                padding: 40px 10px;
                margin-bottom: 40px;
                font-family: 'Red Hat Text', sans-serif;
            }

            .detail-container p {
                font-size: 1.1rem;
                color: #555;
                margin-bottom: 40px;
            }

            .detail-container h2 {
                font-size: 2rem;
                font-weight: 600;
                margin-bottom: 10px;
            }

            .detail-card {
                display: flex;
                min-height: 500px;
                flex-direction: column;
                background-color: #fff;
                padding: 25px;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .detail-card img {
                width: 100%;
                max-height: 500px;
                object-fit: cover;
                border-radius: 8px;
                margin-bottom: 20px;
            }

            .detail-info h3 {
                font-size: 1.8rem;
                font-weight: 600;
                margin-bottom: 30px;
                margin-top: 20px;
                color: #555;
            }

            .detail-info p {
                font-size: 1.05rem;
                margin-bottom: 10px;
                color: #299E63;
            }

            .detail-info div {
                font-size: 1.2rem;
                color: #555;
                margin-left: 20px;
            }

            body {
                font-family: 'Red Hat Text', sans-serif;
            }

            .icon {
                color: #299E63;
                margin-right: 10px;
                font-size: 20px;
                margin-bottom: 12px;
            }

            .status-item {
                font-size: 1rem;
                margin-left: 20px;
                margin-top: 15px;
                margin-bottom: 15px;
                font-family: 'Red Hat Text', sans-serif;
                display: inline-block;
                padding: 8px 12px;
                border-radius: 9px;
                font-weight: 500;
            }

            .status-item i {
                margin-right: 6px;
                font-size: 14px;
            }

            .status-item.belum {
                color: #dc3545;
                background-color: #ffeaea;
            }

            .status-item.sedang {
                color: #ff8c00;
                background: rgba(255, 140, 0, 0.1);
            }

            .status-item.selesai {
                color: #299E63;
                background: rgba(41, 158, 99, 0.1);
            }
        </style>
@endsection