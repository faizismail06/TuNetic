@extends('components.navbar')

@section('content')
    <link
        href="https://fonts.googleapis.com/css2?family=Red+Hat+Text:wght@400;500;600;700&family=Red+Hat+Display:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

    <style>
        /* Reset dan Base Styles */
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            overflow-x: hidden;
            font-family: 'Red Hat Text', sans-serif;
            line-height: 1.6;
        }

        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        /* Custom Container */
        .custom-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Hero Section */
        .hero-section {
            background-color: #299E63;
            color: white;
            position: relative;
            min-height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .hero-logo {
            position: absolute;
            top: 10%;
            left: 0;
            width: 300px;
            z-index: 0;
            opacity: 100;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            padding: 0 20px;
        }

        .hero-title {
            font-family: 'Red Hat Text', sans-serif;
            font-size: 5rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.1;
        }

        .hero-tagline {
            font-family: 'Red Hat Text', sans-serif;
            font-size: 1.4rem;
            margin-top: 10px;
        }

        .hero-character {
            position: absolute;
            bottom: 0;
            right: 0;
            max-width: 47%;
            height: auto;
            z-index: 2;
            object-fit: contain;
        }

        /* Section Styles */
        .section-padding {
            padding: 100px 0;
        }

        .feature-section {
            background: #ffffff;
        }

        .feature-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
            padding: 40px 0;
        }

        .feature-title {
            font-family: 'Red Hat Text', sans-serif;
            font-size: 2.5rem;
            margin-bottom: 40px;
            font-weight: 600;
        }

        .feature-description {
            font-family: 'Red Hat Text', sans-serif;
            font-size: 1.5rem;
            margin-bottom: 40px;
            color: #555;
            line-height: 1.6;
        }

        .feature-btn {
            font-family: 'Red Hat Text', sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            background: linear-gradient(135deg, #299E63 0%, #22824f 100%);
            color: white;
            width: fit-content;
            padding: 16px 32px;
            text-decoration: none;
            border-radius: 12px;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(41, 158, 99, 0.3);
            border: none;
            cursor: pointer;
        }

        .feature-btn:hover {
            background: linear-gradient(135deg, #22824f 0%, #1e6b42 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(41, 158, 99, 0.4);
        }

        .feature-image {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.1));
        }

        /* Status Styles */
        .status {
            font-weight: 500;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 8px;
            border-radius: 6px;
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

        /* Riwayat Section */
        .riwayat-section {
            padding: 100px 0;
            background: white;
        }

        .riwayat-title {
            font-family: 'Red Hat Text', sans-serif;
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 600;
            margin-bottom: 16px;
            color: #2c3e50;
        }

        .riwayat-subtitle {
            font-size: clamp(1rem, 2vw, 1.2rem);
            color: #666;
            margin-bottom: 40px;
        }

        .riwayat-illustration {
            max-width: 90%;
            margin: 0 auto;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.1));
        }

        /* Card Styles */
        .report-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .report-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .report-image {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 12px;
        }

        .report-title {
            font-family: 'Red Hat Text', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 12px;
            color: #2c3e50;
            line-height: 1.3;
        }

        .report-meta {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .report-meta i {
            color: #299E63;
            width: 12px;
        }

        .btn-detail {
            color: #299E63;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .btn-detail:hover {
            color: #22824f;
        }

        .view-all-link {
            color: #299E63;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: color 0.3s ease;
        }

        .view-all-link:hover {
            color: #22824f;
        }

        /* Lacak Section */
        .lacak-section {
            padding: 100px 0;
            background: white;
        }

        .lacak-title {
            font-family: 'Red Hat Text', sans-serif;
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 600;
            margin-bottom: 16px;
            color: #2c3e50;
        }

        .lacak-subtitle {
            font-size: clamp(1rem, 2vw, 1.2rem);
            color: #666;
            margin-bottom: 40px;
        }

        .lacak-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            margin-bottom: 30px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .hero-section {
                min-height: 80vh;
                text-align: center;
                padding: 20px;
            }

            .hero-logo {
                display: none;
            }

            .hero-title {
                font-size: 2.5rem;
                margin-bottom: 10px;
            }

            .hero-tagline {
                font-size: 1.1rem;
            }

            .hero-character {
                position: static;
                max-width: 90%;
                margin: 30px auto 0;
                display: block;
            }

            .feature-section {
                padding: 60px 20px;
            }

            .feature-title {
                font-size: 2rem;
                text-align: center;
                margin-bottom: 30px;
            }

            .feature-description {
                font-size: 1.2rem;
                text-align: center;
                margin-bottom: 30px;
            }

            .feature-btn {
                display: block;
                text-align: center;
                max-width: 250px;
                margin: 0 auto;
            }

            .lacak-section {
                padding: 60px 20px;
            }

            .lacak-image {
                height: 250px;
            }

            .riwayat-title {
                font-size: 2rem;
            }

            .lacak-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-tagline {
                font-size: 1rem;
            }

            .feature-title {
                font-size: 1.8rem;
            }

            .feature-description {
                font-size: 1.1rem;
            }

            .lacak-image {
                height: 200px;
            }

            .riwayat-title {
                font-size: 1.8rem;
            }

            .lacak-title {
                font-size: 1.8rem;
            }
        }

        .hero-img {
            max-width: 80%;
            height: auto;
            max-height: 100vh;
            object-fit: cover;
            position: absolute;
            bottom: 0;
            right: 0;
        }

        .hero-image-container {
            position: relative;
            height: 100vh;
            /* atau sesuai kebutuhan */
            overflow: hidden;
            /* supaya tidak melebar keluar */
        }


        /* Tablet Responsive */
        @media (min-width: 769px) and (max-width: 1024px) {
            .hero-title {
                font-size: 4rem;
            }

            .hero-tagline {
                font-size: 1.3rem;
            }

            .hero-logo {
                width: 250px;
            }

            .hero-character {
                max-width: 50%;
                right: -20px;
            }

            .feature-section {
                padding: 80px 40px;
            }

            .lacak-section {
                padding: 80px 40px;
            }

            #homepage-map {
                height: 400px;
                width: 100%;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            }

            .map-legend {
                position: absolute;
                bottom: 20px;
                right: 20px;
                background-color: white;
                padding: 10px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
                z-index: 1000;
            }

            .legend-item {
                display: flex;
                align-items: center;
                margin-bottom: 5px;
            }

            .legend-icon {
                width: 20px;
                height: 20px;
                display: inline-block;
                margin-right: 8px;
                border-radius: 50%;
            }

            .truck-icon {
                font-size: 20px;
                margin-right: 8px;
            }
        }

        /* Large Desktop Optimization */
        @media (min-width: 1400px) {
            .hero-content {
                padding-left: 60px;
            }

            .section-padding {
                padding: 120px 0;
            }
        }
    </style>

    {{-- Hero Section --}}
    <section class="hero-section">
        <!-- Logo di belakang teks -->
        <img src="{{ asset('assets/images/Masyarakat/aksenlogo.png') }}" alt="Logo Belakang" class="hero-logo">

        <div class="container-fluid">
            <div class="row align-items-center h-100">
                <div class="col-lg-6 col-md-7">
                    <div class="hero-content">
                        <h1 class="hero-title">Selamat Datang<br>di TuNetic</h1>
                        <p class="hero-tagline">#Small Steps, Big Impact</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-5 d-none d-md-flex hero-image-container">
                    <img src="{{ asset('assets/images/Masyarakat/iconpetugas2.png') }}" alt="Petugas TuNetic"
                        class="img-fluid hero-img">
                </div>
            </div>
        </div>
    </section>

    {{-- Lapor Sampah Section --}}
    <section class="feature-section section-padding">
        <div class="custom-container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 order-2 order-lg-1">
                    <div class="text-center">
                        <img src="{{ asset('assets/images/Masyarakat/sampah2.png') }}" alt="Ilustrasi Sampah"
                            class="feature-image">
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2">
                    <div class="feature-content">
                        <h2 class="feature-title">Lapor Sampah</h2>
                        <p class="feature-description">
                            Laporkan lokasi sampah yang perlu dibersihkan dengan mengunggah foto, menambahkan deskripsi,
                            dan penandaan di peta untuk tindakan lanjut yang cepat dan efektif.
                        </p>
                        <a href="/masyarakat/lapor" class="feature-btn">Laporkan Sampah</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Riwayat Pengajuan Section --}}
    <section class="riwayat-section">
        <div class="custom-container">
            <div class="row align-items-start g-5">
                <div class="col-lg-6 order-1">
                    <div class="feature-content">
                        <h2 class="riwayat-title">Riwayat Pengajuan</h2>
                        <p class="riwayat-subtitle">
                            Lihat Status dan Perkembangan Laporan Sampah Anda
                        </p>
                        <div class="text-center d-none d-lg-block mt-4">
                            <img src="{{ asset('assets/images/Masyarakat/petugas3.png') }}" alt="Ilustrasi Petugas"
                                class="riwayat-illustration">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 order-lg-2 pt-lg-5">
                    @if ($laporanTerbaru->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-clipboard-list text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h5 class="text-muted mb-3">Belum Ada Laporan</h5>
                            <p class="text-muted">Mulai laporkan sampah di sekitar Anda untuk menciptakan lingkungan yang
                                lebih
                                bersih.</p>
                            <a href="/masyarakat/lapor" class="feature-btn mt-3">Buat Laporan Pertama</a>
                        </div>
                    @else
                        <div class="d-flex flex-column">
                            @foreach ($laporanTerbaru as $lapor)
                                <div class="report-card">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-4 col-sm-3">
                                            @if ($lapor->gambar)
                                                <img src="{{'storage/laporan_warga/' . $lapor->gambar }}" class="report-image" alt="Gambar Laporan">
                                            @else
                                                <div
                                                    class="report-image bg-light d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-8 col-sm-9">
                                            <h6 class="report-title">{{ $lapor->judul }}</h6>

                                            <div class="report-meta">
                                                <i class="fas fa-calendar-alt"></i>
                                                <span>{{ $lapor->created_at->format('d F Y') }}</span>
                                            </div>

                                            <div class="report-meta">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <span
                                                    class="text-truncate">{{ $lapor->lokasi ?? 'Lokasi tidak tersedia' }}</span>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                @if ($lapor->status == 0)
                                                    <span class="status belum">
                                                        <i class="fas fa-circle-exclamation"></i> Belum diangkut
                                                    </span>
                                                @elseif($lapor->status == 1)
                                                    <span class="status sedang">
                                                        <i class="fas fa-clock"></i> Sedang proses
                                                    </span>
                                                @elseif($lapor->status == 2)
                                                    <span class="status belum">
                                                        <i class="fas fa-circle-xmark"></i> Ditolak
                                                    </span>
                                                @elseif($lapor->status == 3)
                                                    <span class="status selesai">
                                                        <i class="fas fa-check-circle"></i> Sudah diangkut
                                                    </span>
                                                @endif

                                                <a href="{{ route('masyarakat.detailRiwayat', $lapor->id) }}" class="btn-detail">
                                                    Lihat Detail
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-start mt-4">
                            <a href="{{ route('masyarakat.lapor.allhistory') }}" class="view-all-link">
                                Lihat Semua
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Mobile Illustration -->
            <div class="row d-lg-none mt-5">
                <div class="col-12 text-center">
                    <img src="{{ asset('assets/images/Masyarakat/petugas3.png') }}" alt="Ilustrasi Petugas"
                        class="riwayat-illustration">
                </div>
            </div>
        </div>
    </section>

    {{-- Lacak Armada Section --}}
    <section class="lacak-section">
        <div class="custom-container">
            <div class="text-center text-lg-start">
                <h2 class="lacak-title">Lacak Armada</h2>
                <p class="lacak-subtitle">Cek Lokasi Armada dan TPS Hari Ini</p>

                <div class="map-card mb-4">
                    <div id="homepage-map" style="height: 400px; border-radius: 16px; overflow: hidden;"></div>
                </div>

                <div class="text-center text-lg-end mt-4">
                    <a href="{{ route('masyarakat.rute-armada.index') }}" class="feature-btn">
                        <i class="fas fa-route me-1"></i> Detail Rute Lengkap
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('js')
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi peta dengan lokasi default Jawa Tengah
            const homepageMap = L.map('homepage-map').setView([-7.056325, 110.454250], 12);

            // Tambahkan tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(homepageMap);

            // Layer untuk marker
            const markersLayer = L.layerGroup().addTo(homepageMap);

            // Fungsi untuk menampilkan armada dan TPS di peta berdasarkan data jadwal yang sudah ada
            function showArmadaOnMap() {
                // Clear markers
                markersLayer.clearLayers();

                // Array untuk menyimpan semua marker
                const allMarkers = [];

                // Menggunakan data yang sudah ada dari controller untuk index route
                const jadwalData = @json($laporanTerbaru ?? []); // Fallback ke array kosong jika tidak ada

                // Dapatkan hari ini dalam bahasa Indonesia
                const today = new Date().toLocaleDateString('id-ID', {
                    weekday: 'long'
                }).toLowerCase();

                // Buat request untuk mendapatkan data
                fetch('/masyarakat/rute-armada')
                    .then(response => response.text())
                    .then(html => {
                        // Parse data dari atribut script dalam HTML
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        // Cari script yang berisi data
                        const scriptElements = doc.querySelectorAll('script');
                        let armadaData = {};
                        let allTps = {};

                        for (const script of scriptElements) {
                            if (script.textContent.includes('const armadaData =')) {
                                // Extract armadaData
                                try {
                                    const match = script.textContent.match(
                                        /const\s+armadaData\s*=\s*(\{.*?\});/s);
                                    if (match && match[1]) {
                                        armadaData = JSON.parse(match[1]);
                                    }
                                } catch (e) {
                                    console.error('Error parsing armadaData:', e);
                                }
                            }

                            if (script.textContent.includes('const allTps =')) {
                                // Extract allTps
                                try {
                                    const match = script.textContent.match(/const\s+allTps\s*=\s*(\{.*?\});/s);
                                    if (match && match[1]) {
                                        allTps = JSON.parse(match[1]);
                                    }
                                } catch (e) {
                                    console.error('Error parsing allTps:', e);
                                }
                            }
                        }

                        // Tampilkan armada di peta
                        Object.keys(armadaData).forEach(jadwalId => {
                            const armada = armadaData[jadwalId];

                            if (armada.last_location) {
                                const loc = armada.last_location;
                                // Marker truk
                                const truckMarker = L.marker([loc.latitude, loc.longitude], {
                                    icon: L.divIcon({
                                        className: 'truck-marker',
                                        html: `<div style="position: relative;">
                                        <img src="/assets/images/img_truck.png" style="width: 45px; height: auto;">
                                    </div>`,
                                        iconSize: [30, 30]
                                    })
                                });

                                // Popup dengan informasi terbatas
                                truckMarker.bindPopup(`
                                <div style="padding: 10px; min-width: 150px; max-width: 250px;">
                                    <h6 style="margin-bottom: 10px; font-weight: bold; text-align: center;">${armada.no_polisi}</h6>
                                    <p style="margin-bottom: 5px;">Status: ${
                                        armada.status === 0 ? 'Belum Beroperasi' :
                                        armada.status === 1 ? 'Sedang Beroperasi' : 'Selesai Beroperasi'
                                    }</p>
                                </div>
                            `);

                                markersLayer.addLayer(truckMarker);
                                allMarkers.push(truckMarker);
                            }
                        });

                        // Tampilkan TPS di peta
                        Object.keys(allTps).forEach(jadwalId => {
                            const tpsPoints = allTps[jadwalId];

                            tpsPoints.forEach(tps => {
                                if (tps.latitude && tps.longitude) {
                                    // Konfigurasi ikon berdasarkan jenis TPS
                                    const config = {
                                        'TPS': {
                                            icon: 'fa-trash',
                                            color: '#2196f3'
                                        },
                                        'TPST': {
                                            icon: 'fa-recycle',
                                            color: '#4caf50'
                                        },
                                        'TPA': {
                                            icon: 'fa-industry',
                                            color: '#ff9800'
                                        }
                                    };

                                    // Gunakan jenis TPS default jika tidak ada
                                    const tpsType =
                                        'TPS'; // Default ke TPS karena tidak ada jenis dalam data
                                    const tpsConfig = config[tpsType];

                                    const marker = L.marker([tps.latitude, tps.longitude], {
                                        icon: L.divIcon({
                                            className: 'tps-marker',
                                            html: `<div style="background-color: ${tpsConfig.color}; width: 32px; height: 32px; border-radius: 50%; border: 3px solid white; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
                                            <i class="fas ${tpsConfig.icon}"></i>
                                        </div>`,
                                            iconSize: [32, 32],
                                            iconAnchor: [16, 32]
                                        })
                                    });

                                    // Popup dengan informasi terbatas
                                    marker.bindPopup(`
                                    <div style="padding: 10px; min-width: 150px; max-width: 250px;">
                                        <h6 style="margin-bottom: 10px; font-weight: bold; text-align: center;">${tps.nama}</h6>
                                        <p style="margin-bottom: 5px;">Alamat: ${tps.alamat || 'Tidak tersedia'}</p>
                                    </div>
                                `);

                                    markersLayer.addLayer(marker);
                                    allMarkers.push(marker);
                                }
                            });
                        });

                        // Fit bounds jika ada marker
                        if (allMarkers.length > 0) {
                            try {
                                const group = L.featureGroup(allMarkers);
                                homepageMap.fitBounds(group.getBounds().pad(0.1));
                            } catch (e) {
                                console.error('Error setting map bounds:', e);
                                // Fallback: gunakan view default
                                homepageMap.setView([-7.056325, 110.454250], 12);
                            }
                        }

                        // // Tambahkan legenda
                        // addMapLegend(homepageMap);
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        // Fallback dengan geolocation
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(
                                function(position) {
                                    homepageMap.setView([position.coords.latitude, position.coords
                                        .longitude
                                    ], 13);
                                },
                                function(error) {
                                    console.warn('Error mendapatkan lokasi:', error.message);
                                }
                            );
                        }
                        // Tambahkan legenda meskipun data gagal dimuat
                        // addMapLegend(homepageMap);
                    });
            }

            // // Fungsi untuk menambahkan legenda
            // function addMapLegend(map) {
            //     const legend = L.control({
            //         position: 'bottomright'
            //     });
            //     legend.onAdd = function(map) {
            //         const div = L.DomUtil.create('div', 'map-legend');
            //         div.innerHTML = `
        //         <div class="legend-item">
        //             <img src="/assets/images/img_truck.png" style="width: 20px; height: auto; margin-right: 8px;">
        //             <span>Armada Sampah</span>
        //         </div>
        //         <div class="legend-item">
        //             <div class="legend-icon" style="background-color: #2196f3; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">
        //                 <i class="fas fa-trash"></i>
        //             </div>
        //             <span>TPS</span>
        //         </div>
        //         <div class="legend-item">
        //             <div class="legend-icon" style="background-color: #4caf50; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">
        //                 <i class="fas fa-recycle"></i>
        //             </div>
        //             <span>TPST</span>
        //         </div>
        //         <div class="legend-item">
        //             <div class="legend-icon" style="background-color: #ff9800; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">
        //                 <i class="fas fa-industry"></i>
        //             </div>
        //             <span>TPA</span>
        //         </div>
        //     `;
            //         return div;
            //     };
            //     legend.addTo(map);
            // }

            // Panggil fungsi untuk menampilkan data
            showArmadaOnMap();

            // Refresh data setiap 30 detik
            setInterval(showArmadaOnMap, 30000);
        });
    </script>
@endpush
