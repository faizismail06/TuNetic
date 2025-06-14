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
                    <span class="status belum">
                        <i class="fas fa-circle-exclamation icon-status"></i> Belum diangkut
                    </span>
                @elseif($laporan->status == 1)
                    <span class="status sedang">
                        <i class="fas fa-clock icon-status"></i> Sedang proses
                    </span>
                @elseif($laporan->status == 2)
                    <span class="status belum">
                        <i class="fas fa-circle-xmark fa-1.5x icon-status"></i> Ditolak
                    </span>
                @elseif($laporan->status == 3)
                    <span class="status selesai">
                        <i class="fas fa-check-circle text-success icon-status"></i> Sudah diangkut
                    </span>
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
                /* Maksimal tinggi yang diinginkan */
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

            .icon-status {
                margin-left: 20px;
                font-size: 20px;
                margin-bottom: 20px;
                margin-right: 7px;
            }

            .status {
                font-weight: 400;
                font-size: 20px;
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
        </style>
@endsection