@extends('components.navbar')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <!-- Leaflet Routing Machine CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <style>
        /* Tambahkan CSS responsive ini ke dalam tag <style> yang sudah ada */

        /* ===== BASE RESPONSIVE STYLES ===== */
        * {
            box-sizing: border-box;
        }

        /* Container adjustments */
        .content-wrapper {
            margin-top: 20px;
            padding: 0 10px;
        }

        /* ===== MAP RESPONSIVE STYLES ===== */
        #map {
            height: 350px;
            /* Reduced from 450px for mobile */
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            min-height: 300px;
        }

        .map-card {
            background-color: #fff;
            border-radius: 12px;
            /* Reduced from 16px */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 12px;
            /* Reduced from 16px */
            margin-bottom: 15px;
        }

        /* ===== CARD AND LAYOUT RESPONSIVE STYLES ===== */
        .jadwal-card {
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .jadwal-card .card-body {
            padding: 15px;
        }

        .jadwal-card .card-title {
            font-size: 1rem;
            margin-bottom: 10px;
            word-break: break-word;
        }

        .jadwal-card .card-text {
            font-size: 0.875rem;
            line-height: 1.4;
        }

        /* ===== BUTTON RESPONSIVE STYLES ===== */
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 10px;
        }

        .btn-group .btn {
            margin-right: 0;
            margin-bottom: 5px;
            flex: 1;
            min-width: 120px;
            font-size: 0.875rem;
            padding: 8px 12px;
        }

        .show-tps-button {
            width: 100%;
            margin-bottom: 10px;
            font-size: 0.875rem;
            padding: 8px 12px;
        }

        /* ===== TRACKING INFO RESPONSIVE STYLES ===== */
        .tracking-info {
            padding: 12px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #17a2b8;
        }

        .tracking-info p {
            font-size: 0.875rem;
            line-height: 1.4;
            margin-bottom: 10px;
        }

        /* ===== STATUS CONTAINER RESPONSIVE STYLES ===== */
        #status-container {
            margin-bottom: 15px;
            padding: 12px;
            border-radius: 8px;
            background-color: #f8f9fa;
        }

        #status-container .status-indicator {
            width: 12px;
            height: 12px;
            flex-shrink: 0;
        }

        #tracking-status {
            font-size: 0.875rem;
            font-weight: 500;
        }

        #location-info {
            font-size: 0.75rem;
            margin-top: 5px;
            word-break: break-all;
        }

        /* ===== MAP LEGEND RESPONSIVE STYLES ===== */
        .map-legend {
            padding: 8px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            position: absolute;
            bottom: 10px;
            right: 10px;
            z-index: 1000;
            max-width: 150px;
            font-size: 0.75rem;
        }

        .legend-item {
            margin-bottom: 4px;
            display: flex;
            align-items: center;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            display: inline-block;
            margin-right: 4px;
            flex-shrink: 0;
        }

        /* ===== TABLET STYLES (768px - 991px) ===== */
        @media (min-width: 768px) and (max-width: 991px) {
            .content-wrapper {
                margin-top: 25px;
                padding: 0 15px;
            }

            #map {
                height: 400px;
            }

            .map-card {
                padding: 14px;
            }

            .jadwal-card .card-title {
                font-size: 1.1rem;
            }

            .btn-group .btn {
                min-width: 130px;
            }
        }

        /* ===== DESKTOP STYLES (992px and up) ===== */
        @media (min-width: 992px) {
            .content-wrapper {
                margin-top: 30px;
                padding: 0 20px;
            }

            #map {
                height: 450px;
            }

            .map-card {
                padding: 16px;
            }

            .map-legend {
                padding: 10px;
                bottom: 20px;
                right: 20px;
                max-width: 200px;
                font-size: 0.875rem;
            }

            .legend-color {
                width: 15px;
                height: 15px;
                margin-right: 5px;
            }

            .legend-item {
                margin-bottom: 5px;
            }
        }

        /* ===== MOBILE STYLES (max-width: 767px) ===== */
        @media (max-width: 767px) {
            .content-wrapper {
                margin-top: 15px;
                padding: 0 8px;
            }

            .container {
                padding-left: 8px;
                padding-right: 8px;
            }

            /* Map adjustments for mobile */
            #map {
                height: 280px;
                border-radius: 8px;
            }

            .map-card {
                padding: 8px;
                border-radius: 8px;
                margin-bottom: 10px;
            }

            /* Card adjustments */
            .jadwal-card .card-body {
                padding: 12px;
            }

            .jadwal-card .card-title {
                font-size: 0.95rem;
                margin-bottom: 8px;
            }

            .jadwal-card .card-text {
                font-size: 0.8rem;
            }

            /* Button adjustments */
            .btn-group {
                flex-direction: column;
                gap: 8px;
            }

            .btn-group .btn {
                width: 100%;
                min-width: auto;
                font-size: 0.8rem;
                padding: 10px;
            }

            .show-tps-button {
                font-size: 0.8rem;
                padding: 10px;
            }

            /* Tracking info adjustments */
            .tracking-info {
                padding: 10px;
                margin-bottom: 10px;
            }

            .tracking-info p {
                font-size: 0.8rem;
                margin-bottom: 8px;
            }

            .tracking-info .btn {
                margin-right: 5px;
                margin-bottom: 8px;
                font-size: 0.75rem;
                padding: 6px 10px;
            }

            /* Status container adjustments */
            #status-container {
                padding: 10px;
                margin-bottom: 10px;
            }

            #tracking-status {
                font-size: 0.8rem;
            }

            #location-info {
                font-size: 0.7rem;
            }

            /* Map legend adjustments */
            .map-legend {
                padding: 6px;
                bottom: 5px;
                right: 5px;
                max-width: 120px;
                font-size: 0.7rem;
            }

            .legend-color {
                width: 10px;
                height: 10px;
                margin-right: 3px;
            }

            .legend-item {
                margin-bottom: 3px;
            }

            /* Badge adjustments */
            .badge {
                font-size: 0.7rem;
                padding: 3px 6px;
            }

            /* Tracking counter adjustments */
            .tracking-counter {
                font-size: 0.7rem;
                padding: 2px 6px;
                margin-top: 5px;
            }
        }

        /* ===== EXTRA SMALL MOBILE (max-width: 480px) ===== */
        @media (max-width: 480px) {
            .content-wrapper {
                padding: 0 5px;
            }

            #map {
                height: 250px;
            }

            .jadwal-card .card-title {
                font-size: 0.9rem;
            }

            .jadwal-card .card-text {
                font-size: 0.75rem;
            }

            .btn-group .btn {
                font-size: 0.75rem;
                padding: 8px;
            }

            .tracking-info p {
                font-size: 0.75rem;
            }

            .tracking-info .btn {
                font-size: 0.7rem;
                padding: 5px 8px;
            }

            #tracking-status {
                font-size: 0.75rem;
            }

            #location-info {
                font-size: 0.65rem;
            }
        }

        /* ===== LANDSCAPE MODE MOBILE ===== */
        @media (max-width: 767px) and (orientation: landscape) {
            #map {
                height: 200px;
            }

            .tracking-info {
                padding: 8px;
            }

            .jadwal-card .card-body {
                padding: 10px;
            }
        }

        /* ===== UTILITY CLASSES FOR RESPONSIVE ===== */
        .text-responsive {
            font-size: 1rem;
        }

        @media (max-width: 767px) {
            .text-responsive {
                font-size: 0.875rem;
            }
        }

        @media (max-width: 480px) {
            .text-responsive {
                font-size: 0.8rem;
            }
        }

        /* ===== FLEXBOX UTILITIES ===== */
        .d-flex-mobile {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        @media (max-width: 767px) {
            .d-flex-mobile {
                flex-direction: column;
                gap: 5px;
            }
        }

        /* ===== LEAFLET MAP RESPONSIVE OVERRIDES ===== */
        .leaflet-container {
            font-size: 12px;
        }

        @media (max-width: 767px) {
            .leaflet-container {
                font-size: 11px;
            }

            .leaflet-control-zoom {
                font-size: 14px;
            }

            .leaflet-control-zoom a {
                width: 30px;
                height: 30px;
                line-height: 30px;
            }

            .leaflet-popup-content-wrapper {
                border-radius: 6px;
            }

            .leaflet-popup-content {
                font-size: 0.8rem;
                line-height: 1.3;
            }
        }

        /* ===== CUSTOM MARKER RESPONSIVE STYLES ===== */
        .tps-icon {
            background-color: #3388ff;
            border-radius: 50%;
            border: 2px solid white;
            text-align: center;
            color: white;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.4);
            font-size: 12px;
        }

        @media (max-width: 767px) {
            .tps-icon {
                font-size: 10px;
                border-width: 1px;
            }
        }

        /* ===== ANIMATION PERFORMANCE OPTIMIZATION ===== */
        @media (prefers-reduced-motion: reduce) {
            .jadwal-card {
                transition: none;
            }

            @keyframes pulse {

                from,
                to {
                    opacity: 1;
                }
            }
        }

        /* ===== PRINT STYLES ===== */
        @media print {

            .tracking-info .btn,
            .btn-group,
            .show-tps-button {
                display: none;
            }

            #map {
                height: 300px;
                page-break-inside: avoid;
            }

            .jadwal-card {
                page-break-inside: avoid;
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
@endpush
<!-- Perbaikan struktur HTML untuk responsivitas -->
<!-- Ganti bagian @section('content') dengan kode berikut: -->

@section('content')
    <div class="container-fluid content-wrapper py-2 py-md-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="card card-transparent border-0">
                    <div class="card-body p-2 p-md-3">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($jadwalOperasional->isEmpty())
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                Anda tidak memiliki jadwal operasional yang aktif saat ini.
                            </div>
                        @else
                            <!-- Info Petugas - Responsive -->
                            @include('petugas.jadwal-pengambilan.info-petugas')

                            <!-- Tracking Info - Responsive -->
                            <div class="tracking-info">
                                <div class="row g-2 g-md-3">
                                    <div class="col-12">
                                        <p class="mb-2 mb-md-3 text-responsive">
                                            <strong>Petunjuk:</strong> Sistem akan otomatis mengambil lokasi perangkat Anda
                                            setiap 5 detik dan mengirimkannya ke server selama tracking aktif. Pastikan GPS
                                            perangkat
                                            Anda aktif.
                                        </p>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex flex-wrap gap-2">
                                            <button id="toggle-tps" class="btn btn-sm btn-info flex-fill flex-md-grow-0">
                                                <i class="fas fa-eye d-md-none"></i>
                                                <span class="d-none d-md-inline">Tampilkan Semua TPS</span>
                                                <span class="d-md-none">Semua TPS</span>
                                            </button>
                                            <button id="toggle-route"
                                                class="btn btn-sm btn-primary flex-fill flex-md-grow-0">
                                                <i class="fas fa-route d-md-none"></i>
                                                <span class="d-none d-md-inline">Tampilkan Rute</span>
                                                <span class="d-md-none">Rute</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Map Container - Responsive -->
                            <div class="row justify-content-center mb-3 mb-md-4">
                                <div class="col-12">
                                    <div class="card map-card">
                                        <div class="card-body p-0">
                                            <div id="map"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Container - Responsive -->
                            <div id="status-container" class="border rounded bg-light">
                                <div class="d-flex align-items-center flex-wrap">
                                    <div id="status-indicator" class="status-indicator status-inactive me-2"></div>
                                    <span id="tracking-status" class="flex-grow-1">Tracking tidak aktif</span>
                                </div>
                                <div class="mt-2">
                                    <small id="location-info" class="text-muted d-block"></small>
                                </div>
                            </div>

                            <!-- Jadwal Operasional - Responsive Grid -->
                            <div class="mt-3 mt-md-4">
                                <h6 class="mb-3 text-responsive">Jadwal Operasional Anda:</h6>
                                <div class="row g-3">
                                    @foreach ($jadwalOperasional as $jadwal)
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="card jadwal-card h-100 shadow-sm">
                                                <div class="card-body">
                                                    <h6 class="card-title">
                                                        {{ $jadwal->armada->no_polisi ?? 'N/A' }}
                                                    </h6>
                                                    <div class="card-text">
                                                        <div class="mb-2">
                                                            <strong>Rute:</strong>
                                                            <span
                                                                class="d-block d-sm-inline">{{ $jadwal->rute->nama_rute ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="mb-2">
                                                            <strong>Jam Aktif:</strong>
                                                            <span
                                                                class="d-block d-sm-inline">{{ $jadwal->jam_aktif }}</span>
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>Status:</strong>
                                                            <div class="mt-1">
                                                                @if ($jadwal->status == 0)
                                                                    <span class="badge bg-secondary">Belum Berjalan</span>
                                                                @elseif($jadwal->status == 1)
                                                                    <span class="badge bg-success">Sedang Berjalan</span>
                                                                @else
                                                                    <span class="badge bg-primary">Selesai</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- TPS Button - Full width on mobile -->
                                                    {{-- <button
                                                        class="btn btn-sm btn-outline-primary show-tps-button w-100 mb-2"
                                                        data-jadwal-id="{{ $jadwal->id }}">
                                                        <i class="fas fa-map-marker-alt me-1"></i>
                                                        <span class="d-none d-sm-inline">Tampilkan TPS</span>
                                                        <span class="d-sm-none">TPS</span>
                                                    </button> --}}

                                                    <!-- Action Buttons - Responsive Layout -->
                                                    <div class="btn-group w-100" data-id="{{ $jadwal->id }}"
                                                        data-status="{{ $jadwal->status }}">
                                                        @if ($jadwal->status == 0)
                                                            <button class="btn btn-success btn-sm start-tracking w-100">
                                                                <i class="fas fa-play me-1 d-sm-none"></i>
                                                                <span class="d-none d-sm-inline">Mulai Tracking</span>
                                                                <span class="d-sm-none">Mulai</span>
                                                            </button>
                                                        @elseif($jadwal->status == 1)
                                                            <button
                                                                class="btn btn-primary btn-sm start-tracking active flex-fill">
                                                                <i class="fas fa-circle me-1 d-sm-none"></i>
                                                                <span class="d-none d-sm-inline">Tracking Aktif</span>
                                                                <span class="d-sm-none">Aktif</span>
                                                            </button>
                                                            <button class="btn btn-danger btn-sm finish-tracking flex-fill">
                                                                <i class="fas fa-stop me-1 d-sm-none"></i>
                                                                <span class="d-none d-sm-inline">Selesai</span>
                                                                <span class="d-sm-none">Stop</span>
                                                            </button>
                                                            <div class="tracking-counter w-100 text-center">Update ke-0
                                                            </div>
                                                        @else
                                                            <button class="btn btn-secondary btn-sm w-100" disabled>
                                                                <i class="fas fa-check me-1"></i>Selesai
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data TPS dari controller - Tetap sama -->
    <script>
        const tpsData = @json($allTps ?? []);
    </script>
@endsection

@push('js')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <!-- Leaflet Routing Machine -->
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <script>
        // Variabel global
        let map;
        let marker;
        let watchId = null;
        let trackingInterval = null;
        let activeJadwalId = null;
        let offlineQueue = [];
        let trackingCounter = 0;
        let trackingCounterElement = null;
        let tpsMarkers = {};
        let routeControl = null;
        let isRouteVisible = false;
        let isGpsLocationReady = false;
        let pendingRouteDrawing = null;
        let isTpsVisible = false;


        // Tambahkan di bagian atas file JavaScript Anda, setelah deklarasi variabel global

        // Replace the existing OSRM_SERVERS declaration with this updated version
        const OSRM_SERVERS = [{
                url: '//router.project-osrm.org/route/v1',
                name: 'Project OSRM (Utama)',
                timeout: 8000,
                profile: 'driving'
            },
            {
                url: '//routing.openstreetmap.de/routed-car/route/v1',
                name: 'OpenStreetMap DE',
                timeout: 10000,
                profile: 'driving'
            }
        ];

        let currentServerIndex = 0;
        let isRoutingInProgress = false;

        // ===== DEBOUNCE FUNCTION =====
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // ===== RESPONSIVE MAP INITIALIZATION =====
        function initializeResponsiveMap() {
            // Deteksi ukuran layar untuk setting awal map
            const isMobile = window.innerWidth <= 767;
            const isTablet = window.innerWidth >= 768 && window.innerWidth <= 991;

            // Atur zoom level berdasarkan ukuran layar
            let initialZoom = 15;
            if (isMobile) {
                initialZoom = 13;
            } else if (isTablet) {
                initialZoom = 14;
            }

            // Koordinat default (Yogyakarta) jika geolokasi gagal
            const defaultCoords = [-7.056325, 110.454250];

            // Inisialisasi peta dengan setting responsif
            map = L.map('map', {
                zoomControl: !isMobile, // Sembunyikan zoom control di mobile
                scrollWheelZoom: !isMobile, // Disable scroll zoom di mobile
                touchZoom: true,
                dragging: true,
                tap: true,
                tapTolerance: 15
            }).setView(defaultCoords, initialZoom);

            // Tambah zoom control custom untuk mobile
            if (isMobile) {
                L.control.zoom({
                    position: 'topright'
                }).addTo(map);
            }

            // Tile layer dengan optimasi untuk mobile
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: isMobile ? 17 : 19,
                tileSize: isMobile ? 256 : 256,
                detectRetina: true
            }).addTo(map);

            // Variabel untuk menyimpan marker lokasi user
            let userLocationMarker = null;

            // Fungsi untuk mendapatkan dan menampilkan lokasi user
            function getUserLocation() {
                // Cek apakah browser mendukung geolokasi
                if ("geolocation" in navigator) {
                    // Tampilkan loading indicator (opsional)
                    console.log("Mencari lokasi Anda...");

                    // Opsi untuk geolokasi
                    const geoOptions = {
                        enableHighAccuracy: true, // Gunakan GPS jika tersedia
                        timeout: 10000, // Timeout 10 detik
                        maximumAge: 300000 // Cache lokasi selama 5 menit
                    };

                    // Dapatkan posisi user
                    navigator.geolocation.getCurrentPosition(
                        // Success callback
                        function(position) {
                            const userLat = position.coords.latitude;
                            const userLng = position.coords.longitude;
                            const accuracy = position.coords.accuracy;

                            console.log(`Lokasi ditemukan: ${userLat}, ${userLng} (akurasi: ${Math.round(accuracy)}m)`);

                            // Set view ke lokasi user
                            map.setView([userLat, userLng], initialZoom);

                            // Hapus marker sebelumnya jika ada
                            if (userLocationMarker) {
                                map.removeLayer(userLocationMarker);
                            }

                            //         // Buat custom icon untuk lokasi user
                            //         const userIcon = L.divIcon({
                            //             className: 'user-location-marker',
                            //             html: `
                        //         <div style="
                        //             width: 20px;
                        //             height: 20px;
                        //             background: #4285f4;
                        //             border: 3px solid white;
                        //             border-radius: 50%;
                        //             box-shadow: 0 2px 6px rgba(0,0,0,0.3);
                        //             position: relative;
                        //         ">
                        //             <div style="
                        //                 width: 40px;
                        //                 height: 40px;
                        //                 background: rgba(66, 133, 244, 0.2);
                        //                 border-radius: 50%;
                        //                 position: absolute;
                        //                 top: -13px;
                        //                 left: -13px;
                        //                 animation: pulse 2s infinite;
                        //             "></div>
                        //         </div>
                        //         <style>
                        //             @keyframes pulse {
                        //                 0% { transform: scale(0.5); opacity: 1; }
                        //                 100% { transform: scale(2); opacity: 0; }
                        //             }
                        //         </style>
                        //     `,
                            //             iconSize: [20, 20],
                            //             iconAnchor: [10, 10]
                            //         });

                            //         // Tambahkan marker lokasi user
                            //         userLocationMarker = L.marker([userLat, userLng], {
                            //             icon: userIcon,
                            //             title: 'Lokasi Anda'
                            //         }).addTo(map);

                            //         // Tambahkan popup dengan informasi lokasi
                            //         userLocationMarker.bindPopup(`
                        //     <div style="text-align: center;">
                        //         <strong>📍 Lokasi Anda</strong><br>
                        //         <small>Akurasi: ±${Math.round(accuracy)} meter</small>
                        //     </div>
                        // `);

                            // Tambahkan circle untuk menunjukkan akurasi (opsional)
                            if (accuracy < 1000) { // Hanya tampilkan jika akurasi < 1km
                                L.circle([userLat, userLng], {
                                    radius: accuracy,
                                    color: '#4285f4',
                                    fillColor: '#4285f4',
                                    fillOpacity: 0.1,
                                    weight: 1
                                }).addTo(map);
                            }
                        },
                        // Error callback
                        function(error) {
                            console.warn("Error mendapatkan lokasi:", error.message);

                            let errorMessage = "Tidak dapat mendeteksi lokasi Anda. ";

                            switch (error.code) {
                                case error.PERMISSION_DENIED:
                                    errorMessage += "Akses lokasi ditolak.";
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    errorMessage += "Informasi lokasi tidak tersedia.";
                                    break;
                                case error.TIMEOUT:
                                    errorMessage += "Waktu habis saat mencari lokasi.";
                                    break;
                                default:
                                    errorMessage += "Terjadi kesalahan yang tidak diketahui.";
                                    break;
                            }

                            console.log(errorMessage + " Menggunakan lokasi default.");

                            // Tetap gunakan lokasi default jika geolokasi gagal
                            map.setView(defaultCoords, initialZoom);
                        },
                        geoOptions
                    );
                } else {
                    console.warn("Geolokasi tidak didukung oleh browser ini. Menggunakan lokasi default.");
                    map.setView(defaultCoords, initialZoom);
                }
            }

            // Panggil fungsi untuk mendapatkan lokasi user
            getUserLocation();

            // Fungsi untuk refresh lokasi user (bisa dipanggil dari button)
            window.refreshUserLocation = function() {
                getUserLocation();
            };

            // Event listener untuk resize window
            window.addEventListener('resize', debounce(function() {
                handleMapResize();
            }, 250));
        }

        // ===== HANDLE MAP RESIZE =====
        function handleMapResize() {
            if (map) {
                // Invalidate map size
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);

                // Adjust zoom based on new screen size
                const isMobile = window.innerWidth <= 767;
                const currentZoom = map.getZoom();

                if (isMobile && currentZoom > 16) {
                    map.setZoom(15);
                } else if (!isMobile && currentZoom < 13) {
                    map.setZoom(15);
                }
            }
        }

        // ===== RESPONSIVE LEGEND =====
        function addResponsiveLegend() {
            const legend = L.control({
                position: window.innerWidth <= 767 ? 'bottomleft' : 'bottomright'
            });

            legend.onAdd = function(map) {
                const div = L.DomUtil.create('div', 'map-legend');
                const isMobile = window.innerWidth <= 767;

                div.innerHTML = `
            <div class="legend-title"><strong>Legend${isMobile ? '' : ':'}:</strong></div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #3388ff; border-radius: 50%;"></div>
                <span>TPS</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #28a745; border-radius: 50%;"></div>
                <span>${isMobile ? 'Aktif' : 'TPS Aktif'}</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #6c757d; border-radius: 50%;"></div>
                <span>${isMobile ? 'Selesai' : 'TPS Selesai'}</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: blue; height: 3px;"></div>
                <span>Rute</span>
            </div>
        `;
                return div;
            };

            legend.addTo(map);
        }

        // ===== RESPONSIVE TPS MARKERS =====
        function createResponsiveTpsMarker(tps, index, jadwalId) {
            const isMobile = window.innerWidth <= 767;
            const iconSize = isMobile ? [20, 20] : [24, 24];
            const fontSize = isMobile ? '10px' : '12px';

            return L.marker([tps.latitude, tps.longitude], {
                icon: L.divIcon({
                    className: 'tps-icon',
                    html: `<div style="line-height: ${iconSize[0]}px; font-size: ${fontSize};">${index + 1}</div>`,
                    iconSize: iconSize
                })
            });
        }

        // ===== RESPONSIVE POPUP CONTENT =====
        function createResponsivePopupContent(title, content, buttons = []) {
            const isMobile = window.innerWidth <= 767;
            const fontSize = isMobile ? '12px' : '14px';
            const padding = isMobile ? '8px' : '12px';

            let popupHTML = `
        <div style="font-size: ${fontSize}; padding: ${padding};">
            <div style="font-weight: bold; margin-bottom: 8px; color: #333;">
                ${title}
            </div>
            <div style="line-height: 1.4;">
                ${content}
            </div>
    `;

            if (buttons.length > 0) {
                popupHTML += '<div style="margin-top: 10px; text-align: center;">';
                buttons.forEach(button => {
                    const btnSize = isMobile ? 'font-size: 11px; padding: 4px 8px;' :
                        'font-size: 12px; padding: 6px 10px;';
                    popupHTML +=
                        `<button onclick="${button.action}" style="${btnSize} margin: 2px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">${button.text}</button>`;
                });
                popupHTML += '</div>';
            }

            popupHTML += '</div>';
            return popupHTML;
        }

        // ===== RESPONSIVE GEOLOCATION OPTIONS =====
        function getResponsiveGeolocationOptions() {
            const isMobile = window.innerWidth <= 767;

            return {
                enableHighAccuracy: true,
                maximumAge: isMobile ? 5000 : 0,
                timeout: isMobile ? 15000 : 10000
            };
        }

        // Fungsi untuk mendapatkan lokasi awal dan memperbarui peta
        function getInitialLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        if (marker && map) {
                            marker.setLatLng([lat, lng]);
                            map.setView([lat, lng], map.getZoom());

                            document.getElementById('location-info').textContent =
                                `Posisi saat ini: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                        }
                    },
                    function(error) {
                        console.error('Error mendapatkan lokasi:', error);
                        document.getElementById('location-info').textContent =
                            'Tidak dapat mengakses lokasi Anda. Pastikan GPS aktif.';
                    },
                    getResponsiveGeolocationOptions()
                );
            } else {
                alert('Browser Anda tidak mendukung Geolocation');
            }
        }

        function toggleTps() {
            if (isTpsVisible) {
                // Sembunyikan TPS
                hideAllTps();

                // Update teks tombol
                const toggleButton = document.getElementById('toggle-tps');
                toggleButton.innerHTML = `
            <i class="fas fa-eye d-md-none"></i>
            <span class="d-none d-md-inline">Tampilkan Semua TPS</span>
            <span class="d-md-none">Semua TPS</span>
        `;
                toggleButton.classList.remove('btn-secondary');
                toggleButton.classList.add('btn-info');

                isTpsVisible = false;
            } else {
                // Tampilkan TPS
                showAllTps();

                // Update teks tombol
                const toggleButton = document.getElementById('toggle-tps');
                toggleButton.innerHTML = `
            <i class="fas fa-eye-slash d-md-none"></i>
            <span class="d-none d-md-inline">Sembunyikan Semua TPS</span>
            <span class="d-md-none">Sembunyikan</span>
        `;
                toggleButton.classList.remove('btn-info');
                toggleButton.classList.add('btn-secondary');

                isTpsVisible = true;
            }
        }

        function showTpsOnMap(jadwalId) {
            if (tpsMarkers[jadwalId]) {
                tpsMarkers[jadwalId].forEach(marker => map.removeLayer(marker));
            }

            tpsMarkers[jadwalId] = [];
            const tpsPoints = tpsData[jadwalId] || [];

            if (tpsPoints.length === 0) {
                alert('Tidak ada data TPS untuk jadwal ini.');
                return;
            }

            const tpsLayerGroup = L.layerGroup();

            // TPS sudah terurut dari controller berdasarkan kolom urutan
            tpsPoints.forEach((tps, index) => {
                const tpsMarker = createResponsiveTpsMarker(tps, index, jadwalId);

                const popupContent = createResponsivePopupContent(
                    `TPS ${tps.urutan || (index + 1)}`, // Gunakan urutan dari database jika ada, atau fallback ke index+1
                    `${tps.nama}<br>Urutan: ${tps.urutan || (index + 1)}<br>Lat: ${tps.latitude}<br>Lng: ${tps.longitude}`
                );

                tpsMarker.bindPopup(popupContent);
                tpsMarker.addTo(map);
                tpsLayerGroup.addLayer(tpsMarker);
                tpsMarkers[jadwalId].push(tpsMarker);
            });

            const allMarkers = [...tpsMarkers[jadwalId]];
            if (marker) {
                allMarkers.push(marker);
            }
            const tpsGroup = L.featureGroup(allMarkers);
            map.fitBounds(tpsGroup.getBounds(), {
                padding: [50, 50]
            });

            // Perbaikan: Jangan langsung gambar rute, tunggu GPS ready
            console.log('Meminta pembuatan rute, GPS ready:', isGpsLocationReady);
            drawResponsiveRoute(tpsPoints);
        }

        // ===== RESPONSIVE ROUTE DRAWING =====
        // Fungsi utama untuk routing dengan fallback
        function drawResponsiveRoute(tpsPoints) {
            // Hapus rute lama jika ada
            if (routeControl) {
                map.removeControl(routeControl);
                routeControl = null;
            }

            // Hapus polyline sederhana jika ada
            if (window.simplePath) {
                map.removeLayer(window.simplePath);
                window.simplePath = null;
            }

            if (!tpsPoints || tpsPoints.length === 0) {
                return;
            }

            const currentPosition = marker.getLatLng();

            // Pastikan posisi marker bukan posisi default
            const defaultLat = -7.056325;
            const defaultLng = 110.454250;

            if (Math.abs(currentPosition.lat - defaultLat) < 0.001 &&
                Math.abs(currentPosition.lng - defaultLng) < 0.001) {
                console.log('Masih menggunakan posisi default, menunda pembuatan rute...');
                pendingRouteDrawing = tpsPoints;

                // Coba dapatkan lokasi GPS dulu
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            marker.setLatLng([lat, lng]);
                            isGpsLocationReady = true;

                            // Panggil ulang setelah lokasi ready
                            if (pendingRouteDrawing) {
                                drawResponsiveRoute(pendingRouteDrawing);
                                pendingRouteDrawing = null;
                            }
                        },
                        function(error) {
                            console.error('Error mendapatkan lokasi untuk rute:', error);
                            // Jika gagal mendapatkan lokasi, gunakan fallback polyline dengan posisi default
                            drawSimpleRoute(currentPosition, tpsPoints);
                        },
                        getResponsiveGeolocationOptions()
                    );
                }
                return;
            }

            // Setup waypoints
            const waypoints = [L.latLng(currentPosition.lat, currentPosition.lng)];
            tpsPoints.forEach(tps => {
                waypoints.push(L.latLng(tps.latitude, tps.longitude));
            });

            if (waypoints.length < 2) {
                return;
            }

            // Optimasi waypoints jika terlalu banyak
            const optimizedWaypoints = optimizeWaypoints(waypoints);

            // Reset server index dan mulai routing
            currentServerIndex = 0;
            isRoutingInProgress = true;

            // Tampilkan loading indicator
            showRoutingStatus('Mencari rute terbaik...', 'loading');

            // Mulai proses routing dengan fallback
            tryRoutingWithServer(optimizedWaypoints, currentPosition, tpsPoints);
        }
        // Fungsi untuk mencoba routing dengan server tertentu
        function tryRoutingWithServer(waypoints, currentPosition, tpsPoints) {
            if (currentServerIndex >= OSRM_SERVERS.length) {
                console.warn('Semua server OSRM gagal, menggunakan rute sederhana sebagai fallback');
                isRoutingInProgress = false;
                showRoutingStatus('Menggunakan rute sederhana', 'warning');
                drawSimpleRoute(currentPosition, tpsPoints);
                return;
            }

            const server = OSRM_SERVERS[currentServerIndex];
            console.log(`Mencoba server ke-${currentServerIndex + 1}: ${server.name}`);

            // Update status
            showRoutingStatus(`Mencoba ${server.name}...`, 'loading');

            // Hapus kontrol routing sebelumnya jika ada
            if (routeControl) {
                map.removeControl(routeControl);
                routeControl = null;
            }

            const isMobile = window.innerWidth <= 767;
            const lineWeight = isMobile ? 4 : 6;
            const whiteLineWeight = isMobile ? 1 : 2;

            // Setup timeout untuk server ini
            let routeTimedOut = false;
            let routeTimeout = setTimeout(function() {
                routeTimedOut = true;
                console.warn(
                    `Server ${server.name} timeout setelah ${server.timeout}ms, mencoba server berikutnya...`);

                // Hapus kontrol routing yang timeout
                if (routeControl) {
                    map.removeControl(routeControl);
                    routeControl = null;
                }

                // Coba server berikutnya
                currentServerIndex++;
                if (isRoutingInProgress) {
                    tryRoutingWithServer(waypoints, currentPosition, tpsPoints);
                }
            }, server.timeout);

            // Buat routing control dengan server saat ini
            routeControl = L.Routing.control({
                waypoints: waypoints,
                routeWhileDragging: false,
                showAlternatives: false,
                fitSelectedRoutes: !isMobile,
                show: false,
                lineOptions: {
                    styles: [{
                        color: 'blue',
                        opacity: 0.7,
                        weight: lineWeight
                    }, {
                        color: 'white',
                        opacity: 0.5,
                        weight: whiteLineWeight
                    }]
                },
                createMarker: function() {
                    return null; // Jangan buat marker tambahan
                },
                router: L.Routing.osrmv1({
                    serviceUrl: server.url,
                    profile: server.profile,
                    useHints: false,
                    geometryOnly: false,
                    suppressDemoServerWarning: true,
                    timeout: server.timeout - 1000 // Timeout sedikit lebih pendek dari wrapper timeout
                })
            }).addTo(map);

            // Handler ketika rute berhasil ditemukan
            routeControl.on('routesfound', function(e) {
                clearTimeout(routeTimeout);

                if (!routeTimedOut && isRoutingInProgress) {
                    const routes = e.routes;
                    console.log(`✅ Rute berhasil ditemukan menggunakan: ${server.name}`);
                    console.log('Detail rute:', routes);

                    isRoutingInProgress = false;
                    isRouteVisible = true;
                    updateRouteButtonText();

                    // Tampilkan status berhasil
                    showRoutingStatus(`Rute ditemukan via ${server.name}`, 'success');

                    // Simpan informasi server yang berhasil untuk penggunaan berikutnya
                    localStorage.setItem('lastSuccessfulOSRMServer', currentServerIndex.toString());
                }
            });

            // Handler ketika terjadi error routing
            routeControl.on('routingerror', function(e) {
                console.warn(`❌ Routing error dari ${server.name}:`, e.error);
                clearTimeout(routeTimeout);

                if (!routeTimedOut && isRoutingInProgress) {
                    // Hapus kontrol routing yang error
                    if (routeControl) {
                        map.removeControl(routeControl);
                        routeControl = null;
                    }

                    // Coba server berikutnya
                    currentServerIndex++;
                    tryRoutingWithServer(waypoints, currentPosition, tpsPoints);
                }
            });
        }

        // Fungsi untuk mengoptimalkan waypoints (batasi jumlah untuk performa)
        function optimizeWaypoints(waypoints) {
            const MAX_WAYPOINTS = 8; // Batasi maksimal waypoints

            if (waypoints.length <= MAX_WAYPOINTS) {
                return waypoints;
            }

            console.log(`Mengoptimalkan waypoints dari ${waypoints.length} menjadi ${MAX_WAYPOINTS}`);

            // Ambil start point
            const startPoint = waypoints[0];
            let remainingPoints = waypoints.slice(1);

            // Jika terlalu banyak, ambil yang terdekat menggunakan algoritma sederhana
            const optimized = [startPoint];
            let currentPos = startPoint;

            // Nearest neighbor selection
            while (optimized.length < MAX_WAYPOINTS && remainingPoints.length > 0) {
                let nearestIndex = 0;
                let shortestDistance = calculateDistance(currentPos, remainingPoints[0]);

                for (let i = 1; i < remainingPoints.length; i++) {
                    const distance = calculateDistance(currentPos, remainingPoints[i]);
                    if (distance < shortestDistance) {
                        shortestDistance = distance;
                        nearestIndex = i;
                    }
                }

                const nearest = remainingPoints.splice(nearestIndex, 1)[0];
                optimized.push(nearest);
                currentPos = nearest;
            }

            return optimized;
        }

        // Fungsi helper untuk menghitung jarak
        function calculateDistance(point1, point2) {
            const R = 6371; // Radius bumi dalam km
            const dLat = (point2.lat - point1.lat) * Math.PI / 180;
            const dLng = (point2.lng - point1.lng) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(point1.lat * Math.PI / 180) * Math.cos(point2.lat * Math.PI / 180) *
                Math.sin(dLng / 2) * Math.sin(dLng / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        // Fungsi untuk menampilkan status routing
        function showRoutingStatus(message, type = 'info') {
            const statusContainer = document.getElementById('status-container');
            if (!statusContainer) return;

            // Hapus status lama
            const oldStatus = statusContainer.querySelector('.routing-status');
            if (oldStatus) {
                oldStatus.remove();
            }

            // Tentukan class berdasarkan type
            let alertClass = 'alert-info';
            let icon = 'fas fa-info-circle';

            switch (type) {
                case 'loading':
                    alertClass = 'alert-primary';
                    icon = 'fas fa-spinner fa-spin';
                    break;
                case 'success':
                    alertClass = 'alert-success';
                    icon = 'fas fa-check-circle';
                    break;
                case 'warning':
                    alertClass = 'alert-warning';
                    icon = 'fas fa-exclamation-triangle';
                    break;
                case 'error':
                    alertClass = 'alert-danger';
                    icon = 'fas fa-times-circle';
                    break;
            }

            // Buat elemen status
            const statusElement = document.createElement('div');
            statusElement.className = `alert ${alertClass} mt-2 p-2 routing-status`;
            statusElement.innerHTML = `<small><i class="${icon} me-1"></i> ${message}</small>`;

            statusContainer.appendChild(statusElement);

            // Auto remove untuk status success/warning/error setelah beberapa detik
            if (type !== 'loading') {
                setTimeout(() => {
                    if (statusElement && statusElement.parentNode) {
                        statusElement.remove();
                    }
                }, type === 'success' ? 3000 : 5000);
            }
        }

        // Fungsi untuk mendapatkan server terbaik berdasarkan riwayat
        function getPreferredServerIndex() {
            const saved = localStorage.getItem('lastSuccessfulOSRMServer');
            if (saved !== null) {
                const index = parseInt(saved);
                if (index >= 0 && index < OSRM_SERVERS.length) {
                    return index;
                }
            }
            return 0; // Default ke server pertama
        }

        // Modifikasi fungsi drawSimpleRoute untuk menampilkan info yang lebih baik
        function drawSimpleRoute(currentPosition, tpsPoints) {
            // Hapus rute sederhana yang sudah ada jika ada
            if (window.simplePath) {
                map.removeLayer(window.simplePath);
            }

            // Buat array koordinat dari posisi armada (marker) ke semua TPS
            const coordinates = [
                [currentPosition.lat, currentPosition.lng], // Mulai dari posisi armada
                ...tpsPoints.map(tps => [parseFloat(tps.latitude), parseFloat(tps.longitude)])
            ];

            // Buat polyline sederhana
            window.simplePath = L.polyline(coordinates, {
                color: 'orange',
                weight: 4,
                opacity: 0.8,
                dashArray: '10, 10' // Garis putus-putus
            }).addTo(map);

            isRouteVisible = true;
            updateRouteButtonText();

            // Tampilkan pesan fallback
            showRoutingStatus('Menggunakan rute sederhana (garis lurus)', 'warning');
        }

        // Fungsi untuk membatalkan routing yang sedang berjalan
        function cancelRouting() {
            isRoutingInProgress = false;
            currentServerIndex = 0;

            if (routeControl) {
                map.removeControl(routeControl);
                routeControl = null;
            }

            // Hapus status loading
            const statusContainer = document.getElementById('status-container');
            if (statusContainer) {
                const loadingStatus = statusContainer.querySelector('.routing-status');
                if (loadingStatus) {
                    loadingStatus.remove();
                }
            }
        }

        // Inisialisasi: Gunakan server terbaik berdasarkan riwayat
        document.addEventListener('DOMContentLoaded', function() {
            currentServerIndex = getPreferredServerIndex();
            console.log(`Server OSRM yang akan digunakan pertama kali: ${OSRM_SERVERS[currentServerIndex].name}`);
        });

        // ===== RESPONSIVE BUTTON TEXT UPDATES =====
        function updateRouteButtonText() {
            const button = document.getElementById('toggle-route');
            const isMobile = window.innerWidth <= 767;

            if (button) {
                if (isRouteVisible) {
                    button.innerHTML =
                        `<i class="fas fa-eye-slash${isMobile ? ' d-md-none' : ''}"></i><span class="d-none d-md-inline">${isMobile ? 'Sembunyikan' : 'Sembunyikan Rute'}</span><span class="d-md-none">Sembunyikan</span>`;
                } else {
                    button.innerHTML =
                        `<i class="fas fa-route${isMobile ? ' d-md-none' : ''}"></i><span class="d-none d-md-inline">${isMobile ? 'Rute' : 'Tampilkan Rute'}</span><span class="d-md-none">Rute</span>`;
                }
            }
        }

        function checkRouteStatus() {
            if (routeControl && isRouteVisible) {
                updateRouteButtonText();
            } else {
                updateRouteButtonText();
            }
        }

        // Toggle visibilitas rute (PERBAIKAN dengan responsif)
        function toggleRouteVisibility() {
            if (!routeControl) {
                let selectedJadwalId = activeJadwalId;

                if (!selectedJadwalId) {
                    const activeJadwal = document.querySelector('.btn-group[data-status="1"]');
                    if (activeJadwal) {
                        selectedJadwalId = activeJadwal.dataset.id;
                    } else {
                        const firstJadwal = document.querySelector('.btn-group');
                        if (firstJadwal) {
                            selectedJadwalId = firstJadwal.dataset.id;
                        }
                    }
                }

                if (!selectedJadwalId) {
                    alert('Tidak ada jadwal yang dapat ditampilkan rutenya. Silakan pilih jadwal terlebih dahulu.');
                    return;
                }

                if (!tpsData[selectedJadwalId] || tpsData[selectedJadwalId].length === 0) {
                    alert('Tidak ada data TPS untuk jadwal ini. Silakan tampilkan TPS terlebih dahulu.');
                    return;
                }

                showTpsOnMap(selectedJadwalId);

                setTimeout(() => {
                    if (routeControl && isRouteVisible) {
                        updateRouteButtonText();
                    }
                }, 500);

                return;
            }

            if (isRouteVisible) {
                const routingContainer = document.querySelector('.leaflet-routing-container');
                if (routingContainer) {
                    routingContainer.style.display = 'none';
                }

                map.eachLayer(function(layer) {
                    if (layer instanceof L.Polyline && !(layer instanceof L.Marker)) {
                        layer.setStyle({
                            opacity: 0
                        });
                    }
                });

                isRouteVisible = false;
            } else {
                const routingContainer = document.querySelector('.leaflet-routing-container');
                if (routingContainer) {
                    routingContainer.style.display = '';
                }

                map.eachLayer(function(layer) {
                    if (layer instanceof L.Polyline && !(layer instanceof L.Marker)) {
                        layer.setStyle({
                            opacity: 0.7
                        });
                    }
                });

                isRouteVisible = true;
            }
            updateRouteButtonText();
        }

        // Tampilkan semua TPS untuk semua jadwal (dengan responsif marker)
        function showAllTps() {
            Object.values(tpsMarkers).forEach(markers => {
                markers.forEach(marker => map.removeLayer(marker));
            });

            tpsMarkers = {};
            const allTpsPoints = [];

            Object.entries(tpsData).forEach(([jadwalId, tpsPoints]) => {
                tpsMarkers[jadwalId] = [];

                tpsPoints.forEach((tps, index) => {
                    const tpsMarker = createResponsiveTpsMarker(tps, index, jadwalId);

                    const popupContent = createResponsivePopupContent(
                        `TPS ${index + 1}`,
                        `${tps.nama}<br>Jadwal ID: ${jadwalId}`
                    );

                    tpsMarker.bindPopup(popupContent);
                    tpsMarker.addTo(map);
                    tpsMarkers[jadwalId].push(tpsMarker);
                    allTpsPoints.push(tps);
                });
            });

            if (allTpsPoints.length > 0) {
                const allMarkers = Object.values(tpsMarkers).flat();
                const tpsGroup = L.featureGroup(allMarkers);
                map.fitBounds(tpsGroup.getBounds(), {
                    padding: [50, 50]
                });
            }
        }

        // Sembunyikan semua TPS
        function hideAllTps() {
            Object.values(tpsMarkers).forEach(markers => {
                markers.forEach(marker => map.removeLayer(marker));
            });

            tpsMarkers = {};

            if (routeControl) {
                map.removeControl(routeControl);
                routeControl = null;
                isRouteVisible = false;
                updateRouteButtonText();
            }
        }

        // ===== RESPONSIVE TOUCH HANDLERS =====
        function setupResponsiveTouchHandlers() {
            if ('ontouchstart' in window) {
                document.querySelectorAll('.btn').forEach(btn => {
                    btn.addEventListener('touchstart', function(e) {
                        e.preventDefault();
                        this.click();
                    }, {
                        passive: false
                    });
                });

                let touchStartTime = 0;
                let touchStartPos = {
                    x: 0,
                    y: 0
                };

                map.on('touchstart', function(e) {
                    touchStartTime = Date.now();
                    touchStartPos = {
                        x: e.originalEvent.touches[0].clientX,
                        y: e.originalEvent.touches[0].clientY
                    };
                });

                map.on('touchend', function(e) {
                    const touchEndTime = Date.now();
                    const touchDuration = touchEndTime - touchStartTime;

                    if (touchDuration < 200) {
                        const touchEndPos = {
                            x: e.originalEvent.changedTouches[0].clientX,
                            y: e.originalEvent.changedTouches[0].clientY
                        };
                        const distance = Math.sqrt(Math.pow(touchEndPos.x - touchStartPos.x, 2) + Math.pow(
                            touchEndPos.y - touchStartPos.y, 2));

                        if (distance < 10) {
                            console.log('Map tapped on mobile');
                        }
                    }
                });
            }
        }

        // ===== PERFORMANCE OPTIMIZATION =====
        function optimizeForMobile() {
            const isMobile = window.innerWidth <= 767;

            if (isMobile) {
                if (trackingInterval) {
                    clearInterval(trackingInterval);
                    trackingInterval = setInterval(function() {
                        navigator.geolocation.getCurrentPosition(
                            updateLocationAndSend,
                            handleLocationError,
                            getResponsiveGeolocationOptions()
                        );
                    }, 7000);
                }
            }
        }

        // ===== ORIENTATION CHANGE HANDLER =====
        function handleOrientationChange() {
            setTimeout(() => {
                if (map) {
                    map.invalidateSize();

                    const mapElement = document.getElementById('map');
                    if (window.innerWidth <= 767) {
                        if (window.orientation === 90 || window.orientation === -90) {
                            mapElement.style.height = '200px';
                        } else {
                            mapElement.style.height = '280px';
                        }
                    }
                }
            }, 500);
        }

        // Setup event listeners
        function setupEventListeners() {
            document.querySelectorAll('.start-tracking').forEach(button => {
                button.addEventListener('click', function() {
                    const jadwalContainer = this.closest('.btn-group');
                    const jadwalId = jadwalContainer.dataset.id;
                    const jadwalStatus = jadwalContainer.dataset.status;

                    if (jadwalStatus === '0') {
                        trackingCounter = 0;
                    }

                    startTracking(jadwalId);
                });
            });

            document.querySelectorAll('.finish-tracking').forEach(button => {
                button.addEventListener('click', function() {
                    const jadwalContainer = this.closest('.btn-group');
                    const jadwalId = jadwalContainer.dataset.id;
                    finishTracking(jadwalId);
                });
            });

            // Hapus event listener untuk show-tps-button karena tombolnya sudah dihapus

            // Ganti event listener untuk toggle TPS
            document.getElementById('toggle-tps').addEventListener('click', toggleTps);
            document.getElementById('toggle-route').addEventListener('click', toggleRouteVisibility);

            window.addEventListener('beforeunload', function() {
                if (activeJadwalId) {
                    saveOfflineData();
                }
                stopTracking();
            });

            // Additional responsive event listeners
            window.addEventListener('orientationchange', handleOrientationChange);
            window.addEventListener('resize', debounce(function() {
                optimizeForMobile();
                updateRouteButtonText();

                const currentLegend = document.querySelector('.map-legend');
                if (currentLegend) {
                    currentLegend.remove();
                    addResponsiveLegend();
                }
            }, 300));

            setupResponsiveTouchHandlers();
        }

        // Fungsi untuk memulai tracking jadwal tertentu
        function startTracking(jadwalId) {
            if (activeJadwalId && activeJadwalId !== jadwalId) {
                stopTracking();
            }

            activeJadwalId = jadwalId;

            fetch(`/petugas/jadwal-pengambilan/start-tracking/${jadwalId}/`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Tracking dimulai:', data);

                        const jadwalContainer = document.querySelector(`.btn-group[data-id="${jadwalId}"]`);
                        jadwalContainer.dataset.status = '1';

                        updateTrackingButtons(jadwalContainer, true);

                        document.getElementById('status-indicator').classList.remove('status-inactive');
                        document.getElementById('status-indicator').classList.add('status-active');
                        document.getElementById('tracking-status').textContent = 'Tracking aktif';

                        if (!jadwalContainer.querySelector('.tracking-counter')) {
                            const counterElement = document.createElement('div');
                            counterElement.className = 'tracking-counter w-100 text-center';
                            counterElement.textContent = 'Update ke-0';
                            jadwalContainer.appendChild(counterElement);
                        }

                        trackingCounterElement = jadwalContainer.querySelector('.tracking-counter');
                        startGpsTracking();
                        sendOfflineData();
                        showTpsOnMap(jadwalId);

                        if (!isTpsVisible) {
                            toggleTps();
                        }
                    } else {
                        console.error('Gagal memulai tracking:', data.message);
                        alert('Gagal memulai tracking: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error saat memulai tracking:', error);
                    alert('Terjadi kesalahan saat memulai tracking.');

                    if (!navigator.onLine) {
                        offlineQueue.push({
                            type: 'start',
                            jadwalId: jadwalId,
                            timestamp: new Date().getTime()
                        });

                        saveOfflineData();
                        startGpsTracking();

                        const jadwalContainer = document.querySelector(`.btn-group[data-id="${jadwalId}"]`);
                        updateTrackingButtons(jadwalContainer, true);

                        document.getElementById('status-indicator').classList.remove('status-inactive');
                        document.getElementById('status-indicator').classList.add('status-active');
                        document.getElementById('tracking-status').textContent = 'Tracking aktif (offline mode)';
                    }
                });
        }

        // Fungsi untuk menyelesaikan tracking
        function finishTracking(jadwalId) {
            if (confirm('Apakah Anda yakin ingin menyelesaikan tracking ini?')) {
                fetch(`/petugas/jadwal-pengambilan/finish-tracking/${jadwalId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Tracking selesai:', data);

                            stopTracking();

                            const jadwalContainer = document.querySelector(`.btn-group[data-id="${jadwalId}"]`);
                            jadwalContainer.dataset.status = '2';

                            jadwalContainer.innerHTML =
                                '<button class="btn btn-secondary btn-sm w-100" disabled><i class="fas fa-check me-1"></i>Selesai</button>';

                            document.getElementById('status-indicator').classList.remove('status-active');
                            document.getElementById('status-indicator').classList.add('status-inactive');
                            document.getElementById('tracking-status').textContent = 'Tracking tidak aktif';

                            activeJadwalId = null;
                        } else {
                            console.error('Gagal menyelesaikan tracking:', data.message);
                            alert('Gagal menyelesaikan tracking: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error saat menyelesaikan tracking:', error);
                        alert('Terjadi kesalahan saat menyelesaikan tracking.');

                        if (!navigator.onLine) {
                            offlineQueue.push({
                                type: 'finish',
                                jadwalId: jadwalId,
                                timestamp: new Date().getTime()
                            });

                            saveOfflineData();
                            stopTracking();

                            const jadwalContainer = document.querySelector(`.btn-group[data-id="${jadwalId}"]`);
                            jadwalContainer.innerHTML =
                                '<button class="btn btn-secondary btn-sm w-100" disabled>Selesai (offline)</button>';

                            document.getElementById('status-indicator').classList.remove('status-active');
                            document.getElementById('status-indicator').classList.add('status-inactive');
                            document.getElementById('tracking-status').textContent = 'Tracking tidak aktif';

                            activeJadwalId = null;
                        }
                    });
            }
        }

        // Fungsi untuk memulai GPS tracking (dengan responsif)
        function startGpsTracking() {
            if (trackingInterval) {
                clearInterval(trackingInterval);
            }

            if (watchId) {
                navigator.geolocation.clearWatch(watchId);
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    updateLocationAndSend,
                    handleLocationError,
                    getResponsiveGeolocationOptions()
                );

                const isMobile = window.innerWidth <= 767;
                const interval = isMobile ? 7000 : 5000;

                trackingInterval = setInterval(function() {
                    navigator.geolocation.getCurrentPosition(
                        updateLocationAndSend,
                        handleLocationError,
                        getResponsiveGeolocationOptions()
                    );
                }, interval);
            } else {
                alert('Browser Anda tidak mendukung Geolocation');
            }
        }

        // Perbaiki fungsi updateLocationAndSend untuk menandai GPS ready
        function updateLocationAndSend(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // Tandai GPS sudah ready
            if (!isGpsLocationReady) {
                isGpsLocationReady = true;
                console.log('GPS location ready, posisi:', lat, lng);

                // Jika ada rute yang pending, gambar sekarang
                if (pendingRouteDrawing) {
                    console.log('Menggambar rute yang tertunda...');
                    setTimeout(() => {
                        drawResponsiveRoute(pendingRouteDrawing);
                        pendingRouteDrawing = null;
                    }, 1000);
                }
            }

            if (marker && map) {
                marker.setLatLng([lat, lng]);

                if (!map.getBounds().contains(marker.getLatLng())) {
                    map.panTo(marker.getLatLng());
                }

                document.getElementById('location-info').textContent =
                    `Posisi saat ini: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;

                // Perbarui rute jika sedang aktif dan GPS sudah ready
                if (isRouteVisible && activeJadwalId && tpsData[activeJadwalId] && isGpsLocationReady) {
                    drawResponsiveRoute(tpsData[activeJadwalId]);
                }
            }

            if (activeJadwalId) {
                sendLocationToServer(lat, lng);

                trackingCounter++;
                if (trackingCounterElement) {
                    trackingCounterElement.textContent = `Update ke-${trackingCounter}`;
                }

                updateTpsStatusBasedOnLocation(lat, lng);
            }
        }

        // Fungsi untuk memperbarui status TPS berdasarkan kedekatan dengan lokasi saat ini
        function updateTpsStatusBasedOnLocation(lat, lng) {
            if (!activeJadwalId || !tpsMarkers[activeJadwalId]) return;

            const tpsPoints = tpsData[activeJadwalId] || [];
            const currentLatLng = L.latLng(lat, lng);
            const THRESHOLD_DISTANCE = 50;

            tpsPoints.forEach((tps, index) => {
                const tpsLatLng = L.latLng(tps.latitude, tps.longitude);
                const distance = currentLatLng.distanceTo(tpsLatLng);

                if (distance <= THRESHOLD_DISTANCE) {
                    const tpsMarker = tpsMarkers[activeJadwalId][index];

                    if (tpsMarker && !tpsMarker.options.icon.options.className.includes('marker-active') &&
                        !tpsMarker.options.icon.options.className.includes('marker-completed')) {

                        map.removeLayer(tpsMarker);

                        const isMobile = window.innerWidth <= 767;
                        const iconSize = isMobile ? [20, 20] : [24, 24];
                        const fontSize = isMobile ? '10px' : '12px';

                        const newMarker = L.marker([tps.latitude, tps.longitude], {
                            icon: L.divIcon({
                                className: 'tps-icon marker-active',
                                html: `<div style="line-height: ${iconSize[0]}px; font-size: ${fontSize};">${index + 1}</div>`,
                                iconSize: iconSize
                            })
                        });

                        const popupContent = createResponsivePopupContent(
                            `TPS ${index + 1}`,
                            `${tps.nama}<br>Status: Aktif/Dikunjungi`
                        );

                        newMarker.bindPopup(popupContent);
                        newMarker.addTo(map);

                        tpsMarkers[activeJadwalId][index] = newMarker;
                        newMarker.openPopup();
                    }
                }
            });
        }

        // Mengirim lokasi ke server
        function sendLocationToServer(lat, lng) {
            const locationData = {
                id_jadwal_operasional: activeJadwalId,
                latitude: lat,
                longitude: lng
            };

            fetch('/petugas/jadwal-pengambilan/save-location', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(locationData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Lokasi berhasil disimpan:', data);
                    } else {
                        console.error('Gagal menyimpan lokasi:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error saat menyimpan lokasi:', error);

                    if (!navigator.onLine) {
                        offlineQueue.push({
                            type: 'location',
                            data: locationData,
                            timestamp: new Date().getTime()
                        });

                        saveOfflineData();
                    }
                });
        }

        // Hentikan tracking
        function stopTracking() {
            if (trackingInterval) {
                clearInterval(trackingInterval);
                trackingInterval = null;
            }

            if (watchId) {
                navigator.geolocation.clearWatch(watchId);
                watchId = null;
            }

            trackingCounter = 0;
            trackingCounterElement = null;
            activeJadwalId = null;
        }

        // Penanganan error lokasi
        function handleLocationError(error) {
            console.error('Error Geolocation:', error);

            let errorMessage;
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage = "User menolak permintaan Geolocation.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage = "Informasi lokasi tidak tersedia.";
                    break;
                case error.TIMEOUT:
                    errorMessage = "Waktu permintaan untuk mendapatkan lokasi user habis.";
                    break;
                case error.UNKNOWN_ERROR:
                    errorMessage = "Terjadi kesalahan yang tidak diketahui.";
                    break;
            }

            document.getElementById('location-info').textContent = errorMessage;
        }

        // Update tampilan tombol tracking
        function updateTrackingButtons(container, isActive) {
            if (isActive) {
                container.innerHTML = `
            <button class="btn btn-primary btn-sm start-tracking active flex-fill">
                <i class="fas fa-circle me-1 d-sm-none"></i>
                <span class="d-none d-sm-inline">Tracking Aktif</span>
                <span class="d-sm-none">Aktif</span>
            </button>
            <button class="btn btn-danger btn-sm finish-tracking flex-fill">
                <i class="fas fa-stop me-1 d-sm-none"></i>
                <span class="d-none d-sm-inline">Selesai</span>
                <span class="d-sm-none">Stop</span>
            </button>
            <div class="tracking-counter w-100 text-center">Update ke-${trackingCounter}</div>
        `;

                container.querySelector('.finish-tracking').addEventListener('click', function() {
                    const jadwalId = container.dataset.id;
                    finishTracking(jadwalId);
                });
            } else {
                if (container.dataset.status === '0') {
                    container.innerHTML = `
                <button class="btn btn-success btn-sm start-tracking w-100">
                    <i class="fas fa-play me-1 d-sm-none"></i>
                    <span class="d-none d-sm-inline">Mulai Tracking</span>
                    <span class="d-sm-none">Mulai</span>
                </button>
            `;

                    container.querySelector('.start-tracking').addEventListener('click', function() {
                        const jadwalId = container.dataset.id;
                        startTracking(jadwalId);
                    });
                } else if (container.dataset.status === '2') {
                    container.innerHTML =
                        '<button class="btn btn-secondary btn-sm w-100" disabled><i class="fas fa-check me-1"></i>Selesai</button>';
                }
            }
        }

        // Perbaiki checkActiveTracking untuk menangani rute yang benar
        function checkActiveTracking() {
            document.querySelectorAll('.btn-group').forEach(container => {
                const jadwalStatus = container.dataset.status;

                if (jadwalStatus === '1') {
                    const jadwalId = container.dataset.id;
                    activeJadwalId = jadwalId;

                    startGpsTracking();
                    updateTrackingButtons(container, true);

                    document.getElementById('status-indicator').classList.remove('status-inactive');
                    document.getElementById('status-indicator').classList.add('status-active');
                    document.getElementById('tracking-status').textContent = 'Tracking aktif';

                    trackingCounterElement = container.querySelector('.tracking-counter');

                    // Tampilkan TPS tapi tunggu GPS ready untuk rute
                    showTpsOnMap(jadwalId);
                }
            });
        }

        // Fungsi untuk menyimpan data offline ke localStorage
        function saveOfflineData() {
            if (offlineQueue.length > 0) {
                localStorage.setItem('tracking_offline_data', JSON.stringify({
                    activeJadwalId: activeJadwalId,
                    queue: offlineQueue,
                    counter: trackingCounter
                }));
            }
        }

        // Fungsi untuk memuat data offline dari localStorage
        function loadOfflineData() {
            const offlineData = localStorage.getItem('tracking_offline_data');

            if (offlineData) {
                try {
                    const parsedData = JSON.parse(offlineData);
                    activeJadwalId = parsedData.activeJadwalId;
                    offlineQueue = parsedData.queue || [];
                    trackingCounter = parsedData.counter || 0;

                    console.log('Data offline dimuat:', parsedData);
                } catch (e) {
                    console.error('Error saat memuat data offline:', e);
                }
            }
        }

        // Fungsi untuk mengirim data offline ke server
        function sendOfflineData() {
            if (!navigator.onLine || offlineQueue.length === 0) {
                return;
            }

            console.log('Mengirim data offline:', offlineQueue);

            const promises = [];

            offlineQueue.forEach(item => {
                let promise;

                if (item.type === 'location') {
                    promise = fetch('/petugas/jadwal-pengambilan/save-location', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(item.data)
                    });
                } else if (item.type === 'start') {
                    promise = fetch(`/petugas/jadwal-pengambilan/start-tracking/${item.jadwalId}/`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });
                } else if (item.type === 'finish') {
                    promise = fetch(`/petugas/jadwal-pengambilan/finish-tracking/${item.jadwalId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });
                }

                if (promise) {
                    promises.push(promise);
                }
            });

            Promise.all(promises)
                .then(() => {
                    offlineQueue = [];
                    localStorage.removeItem('tracking_offline_data');
                    console.log('Semua data offline berhasil dikirim');
                })
                .catch(error => {
                    console.error('Error saat mengirim data offline:', error);
                });
        }

        // Deteksi koneksi online/offline
        window.addEventListener('online', function() {
            console.log('Kembali online');
            document.getElementById('tracking-status').textContent = activeJadwalId ?
                'Tracking aktif' : 'Tracking tidak aktif';

            sendOfflineData();
        });

        window.addEventListener('offline', function() {
            console.log('Koneksi offline');
            if (activeJadwalId) {
                document.getElementById('tracking-status').textContent = 'Tracking aktif (offline mode)';
            }
        });

        // Enhanced location tracking dengan compass surrogate
        function enhancedLocationTracking() {
            let locationHistory = [];
            let currentBearing = 0;
            const MIN_SPEED_FOR_BEARING = 1.5;

            function startEnhancedTracking() {
                if (trackingInterval) {
                    clearInterval(trackingInterval);
                }

                if (watchId) {
                    navigator.geolocation.clearWatch(watchId);
                }

                locationHistory = [];

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const initialPosition = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                                timestamp: position.timestamp,
                                accuracy: position.coords.accuracy,
                                speed: position.coords.speed || 0
                            };
                            locationHistory.push(initialPosition);
                            updateLocationAndSendEnhanced(position);
                        },
                        handleLocationError,
                        getResponsiveGeolocationOptions()
                    );

                    const isMobile = window.innerWidth <= 767;
                    const interval = isMobile ? 7000 : 5000;

                    trackingInterval = setInterval(function() {
                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                updateLocationAndSendEnhanced(position);
                            },
                            handleLocationError,
                            getResponsiveGeolocationOptions()
                        );
                    }, interval);
                } else {
                    alert('Browser Anda tidak mendukung Geolocation');
                }
            }

            function calculateBearing(startLat, startLng, endLat, endLng) {
                startLat = toRadians(startLat);
                startLng = toRadians(startLng);
                endLat = toRadians(endLat);
                endLng = toRadians(endLng);

                const y = Math.sin(endLng - startLng) * Math.cos(endLat);
                const x = Math.cos(startLat) * Math.sin(endLat) -
                    Math.sin(startLat) * Math.cos(endLat) * Math.cos(endLng - startLng);
                let bearing = Math.atan2(y, x);

                bearing = toDegrees(bearing);
                return (bearing + 360) % 360;
            }

            function toRadians(degrees) {
                return degrees * (Math.PI / 180);
            }

            function toDegrees(radians) {
                return radians * (180 / Math.PI);
            }

            function updateLocationAndSendEnhanced(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const accuracy = position.coords.accuracy;
                const speed = position.coords.speed || 0;
                const timestamp = position.timestamp;

                if (accuracy > 100) {
                    console.log(`Mengabaikan lokasi dengan akurasi rendah: ${accuracy}m`);
                    document.getElementById('location-info').textContent =
                        `Mencari sinyal GPS yang lebih akurat... (${accuracy.toFixed(1)}m)`;
                    return;
                }

                locationHistory.push({
                    lat,
                    lng,
                    timestamp,
                    accuracy,
                    speed
                });

                if (locationHistory.length > 5) {
                    locationHistory.shift();
                }

                if (locationHistory.length >= 2 && speed >= MIN_SPEED_FOR_BEARING) {
                    const prevLocation = locationHistory[locationHistory.length - 2];

                    const newBearing = calculateBearing(
                        prevLocation.lat, prevLocation.lng,
                        lat, lng
                    );

                    if (Math.abs(newBearing - currentBearing) < 180) {
                        currentBearing = currentBearing * 0.7 + newBearing * 0.3;
                    } else {
                        currentBearing = newBearing;
                    }
                }

                if (marker && map) {
                    marker.setLatLng([lat, lng]);

                    if (speed >= MIN_SPEED_FOR_BEARING) {
                        const isMobile = window.innerWidth <= 767;
                        const markerSize = isMobile ? [24, 24] : [30, 30];

                        const markerIcon = L.divIcon({
                            className: 'tps-icon',
                            html: `<div style="transform: rotate(${currentBearing}deg); line-height: ${markerSize[0]}px;"><i class="fas fa-truck"></i></div>`,
                            iconSize: markerSize
                        });
                        marker.setIcon(markerIcon);
                    }

                    if (!map.getBounds().contains(marker.getLatLng())) {
                        map.panTo(marker.getLatLng());
                    }

                    document.getElementById('location-info').textContent =
                        `Posisi: ${lat.toFixed(6)}, ${lng.toFixed(6)} | Akurasi: ${accuracy.toFixed(1)}m | Arah: ${currentBearing.toFixed(0)}° | Kecepatan: ${(speed * 3.6).toFixed(1)} km/h`;

                    if (isRouteVisible && activeJadwalId && tpsData[activeJadwalId]) {
                        if (locationHistory.length >= 2) {
                            const prevLocation = locationHistory[locationHistory.length - 2];
                            const distance = L.latLng(prevLocation.lat, prevLocation.lng).distanceTo(L.latLng(lat, lng));

                            if (distance > 20) {
                                drawResponsiveRoute(tpsData[activeJadwalId]);
                            }
                        }
                    }
                }

                if (activeJadwalId) {
                    sendEnhancedLocationToServer(lat, lng, accuracy, currentBearing, speed);

                    trackingCounter++;
                    if (trackingCounterElement) {
                        trackingCounterElement.textContent = `Update ke-${trackingCounter}`;
                    }

                    updateTpsStatusBasedOnLocation(lat, lng);
                }
            }

            function sendEnhancedLocationToServer(lat, lng, accuracy, bearing, speed) {
                const locationData = {
                    id_jadwal_operasional: activeJadwalId,
                    latitude: lat,
                    longitude: lng,
                    accuracy: accuracy,
                    bearing: bearing,
                    speed: speed
                };

                fetch('/petugas/jadwal-pengambilan/save-location', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(locationData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Lokasi yang ditingkatkan berhasil disimpan:', data);
                        } else {
                            console.error('Gagal menyimpan lokasi:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error saat menyimpan lokasi:', error);

                        if (!navigator.onLine) {
                            offlineQueue.push({
                                type: 'location',
                                data: locationData,
                                timestamp: new Date().getTime()
                            });

                            saveOfflineData();
                        }
                    });
            }

            return {
                startEnhancedTracking: startEnhancedTracking,
            };
        }

        // Inisialisasi enhanced location tracker
        const locationTracker = enhancedLocationTracking();

        // Override startGpsTracking dengan versi enhanced
        function startGpsTracking() {
            locationTracker.startEnhancedTracking();
        }

        // Inisialisasi pada DOMContentLoaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize responsive map
            initializeResponsiveMap();

            // Add responsive legend
            addResponsiveLegend();

            // Create responsive marker
            const isMobile = window.innerWidth <= 767;
            const markerSize = isMobile ? [24, 24] : [30, 30];

            marker = L.marker([-7.056325, 110.454250], {
                icon: L.divIcon({
                    className: 'tps-icon',
                    html: '<i class="fas fa-truck"></i>',
                    iconSize: markerSize
                })
            }).addTo(map);
            marker.bindPopup('Posisi Anda');

            // Load other functionalities
            loadOfflineData();
            setupEventListeners();
            checkActiveTracking();
            optimizeForMobile();

            // Get initial location
            getInitialLocation();

            console.log('Responsive map initialized');
        });

        // Export responsive functions
        window.handleMapResize = handleMapResize;
        window.optimizeForMobile = optimizeForMobile;
        window.createResponsivePopupContent = createResponsivePopupContent;
    </script>
@endpush
