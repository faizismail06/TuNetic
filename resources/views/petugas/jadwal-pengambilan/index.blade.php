@extends('components.navbar')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <!-- Leaflet Routing Machine CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <style>
        #map {
            height: 450px;
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
        }

        .map-card {
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 16px;
        }

        .status-indicator {
            width: 15px;
            height: 15px;
            display: inline-block;
            border-radius: 50%;
            margin-right: 5px;
        }

        .status-active {
            background-color: #28a745;
            animation: pulse 1.5s infinite;
        }

        .status-inactive {
            background-color: #dc3545;
        }

        .jadwal-card {
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .jadwal-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .tracking-info {
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #17a2b8;
        }

        .tracking-counter {
            display: inline-block;
            padding: 3px 8px;
            background-color: #f0f0f0;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-left: 10px;
        }

        /* Custom marker styling */
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
        }

        .marker-active {
            background-color: #28a745;
            z-index: 1000 !important;
        }

        .marker-completed {
            background-color: #6c757d;
        }

        .map-legend {
            padding: 10px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            position: absolute;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        .legend-item {
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }

        .legend-color {
            width: 15px;
            height: 15px;
            display: inline-block;
            margin-right: 5px;
        }

        .btn-group .btn {
            margin-right: 5px;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, .125);
        }

        .content-wrapper {
            margin-top: 30px;
            /* Sesuaikan dengan tinggi navbar */
        }

        .card-transparent {
            background-color: transparent;
            border: none;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container content-wrapper py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-11">
                <div class="card card-transparent border-0">
                    {{-- <div class="card-header bg-transparent border-0">
                        <h1 class="mb-0 ms-2 w-100 fs-4 fw-bold">Rute Jemputan Sampah Hari
                            {{ ucfirst(request()->day ?? 'Selasa') }}
                        </h1>
                    </div> --}}
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if ($jadwalOperasional->isEmpty())
                            <div class="alert alert-info">
                                Anda tidak memiliki jadwal operasional yang aktif saat ini.
                            </div>
                        @else
                            <!-- Tambahkan komponen info petugas di sini -->
                            @include('petugas.jadwal-pengambilan.info-petugas')
                            <div class="tracking-info mb-3">
                                <p>
                                    <strong>Petunjuk:</strong> Sistem akan otomatis mengambil lokasi perangkat Anda
                                    setiap 5
                                    detik dan mengirimkannya ke server selama tracking aktif. Pastikan GPS perangkat
                                    Anda
                                    aktif.
                                </p>
                                <div class="mt-2">
                                    <button id="show-all-tps" class="btn btn-sm btn-info">Tampilkan Semua TPS</button>
                                    <button id="hide-all-tps" class="btn btn-sm btn-secondary ml-2">Sembunyikan
                                        TPS</button>
                                    <button id="toggle-route" class="btn btn-sm btn-primary ml-2">Tampilkan
                                        Rute</button>
                                </div>
                            </div>

                            <div class="container d-flex justify-content-center mb-4">
                                <div class="col-lg-12">
                                    <div class="card map-card">
                                        <div class="card-body p-0">
                                            <div id="map"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="status-container" class="mb-3 p-2 border rounded bg-light">
                                <div class="d-flex align-items-center">
                                    <div id="status-indicator" class="status-indicator status-inactive"></div>
                                    <span id="tracking-status" class="ms-2">Tracking tidak aktif</span>
                                </div>
                                <small id="location-info" class="text-muted d-block mt-1"></small>
                            </div>

                            <div>
                                <h6 class="mb-3">Jadwal Operasional Anda:</h6>
                                <div class="row">
                                    @foreach ($jadwalOperasional as $jadwal)
                                        <div class="col-md-6 mb-3">
                                            <div class="card jadwal-card h-100 shadow-sm">
                                                <div class="card-body">
                                                    <h6 class="card-title">
                                                        {{ $jadwal->armada->no_polisi ?? 'N/A' }} -
                                                        {{-- {{ $jadwal->jadwal->nama_jadwal ?? 'N/A' }} --}}
                                                    </h6>
                                                    <p class="card-text">
                                                        <small>
                                                            <strong>Rute:</strong>
                                                            {{ $jadwal->ruteTps->rute->nama_rute ?? 'N/A' }}<br>
                                                            <strong>Jam Aktif:</strong> {{ $jadwal->jam_aktif }}<br>
                                                            <strong>Status:</strong>
                                                            @if ($jadwal->status == 0)
                                                                <span class="badge bg-secondary">Belum Berjalan</span>
                                                            @elseif($jadwal->status == 1)
                                                                <span class="badge bg-success">Sedang Berjalan</span>
                                                            @else
                                                                <span class="badge bg-primary">Selesai</span>
                                                            @endif
                                                        </small>
                                                    </p>
                                                    <button class="btn btn-sm btn-outline-primary show-tps-button "
                                                        data-jadwal-id="{{ $jadwal->id }}">
                                                        Tampilkan TPS
                                                    </button>
                                                    <div class="btn-group jadwal-buttons" data-id="{{ $jadwal->id }}"
                                                        data-status="{{ $jadwal->status }}">
                                                        @if ($jadwal->status == 0)
                                                            <button class="btn btn-success btn-sm start-tracking">Mulai
                                                                Tracking</button>
                                                        @elseif($jadwal->status == 1)
                                                            <button
                                                                class="btn btn-primary btn-sm start-tracking active">Tracking
                                                                Aktif</button>
                                                            <button
                                                                class="btn btn-danger btn-sm finish-tracking">Selesai</button>
                                                            <div class="tracking-counter mt-2 small text-muted">Update ke-0
                                                            </div>
                                                        @else
                                                            <button class="btn btn-secondary btn-sm"
                                                                disabled>Selesai</button>
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

    <!-- Data TPS dari controller -->
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

        // Inisialisasi peta
        document.addEventListener('DOMContentLoaded', function() {
            initializeMap();
            loadOfflineData();
            setupEventListeners();
            checkActiveTracking();
        });

        // Fungsi untuk inisialisasi peta
        function initializeMap() {
            // Inisialisasi peta dengan lokasi default (Jawa Tengah)
            map = L.map('map').setView([-7.056325, 110.454250], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Tambahkan legenda ke peta
            addMapLegend();

            // Buat marker dengan posisi awal
            marker = L.marker([-7.056325, 110.454250], {
                icon: L.divIcon({
                    className: 'tps-icon',
                    html: '<i class="fas fa-truck"></i>',
                    iconSize: [30, 30]
                })
            }).addTo(map);
            marker.bindPopup('Posisi Anda');

            // Dapatkan lokasi pengguna
            getInitialLocation();
        }

        // Tambahkan legenda ke peta
        function addMapLegend() {
            const legend = L.control({
                position: 'bottomright'
            });

            legend.onAdd = function(map) {
                const div = L.DomUtil.create('div', 'map-legend');
                div.innerHTML = `
                            <div class="legend-title"><strong>Legend:</strong></div>
                            <div class="legend-item">
                                <div class="legend-color" style="background-color: #3388ff; border-radius: 50%;"></div>
                                <span>TPS</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background-color: #28a745; border-radius: 50%;"></div>
                                <span>TPS Aktif</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background-color: #6c757d; border-radius: 50%;"></div>
                                <span>TPS Selesai</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background-color: blue; height: 3px;"></div>
                                <span>Rute Jalan</span>
                            </div>
                        `;
                return div;
            };

            legend.addTo(map);
        }

        // Fungsi untuk mendapatkan lokasi awal dan memperbarui peta
        function getInitialLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        // Perbarui posisi marker dan view peta
                        if (marker && map) {
                            marker.setLatLng([lat, lng]);
                            map.setView([lat, lng], 15);

                            // Update info lokasi
                            document.getElementById('location-info').textContent =
                                `Posisi saat ini: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                        }
                    },
                    function(error) {
                        console.error('Error mendapatkan lokasi:', error);
                        document.getElementById('location-info').textContent =
                            'Tidak dapat mengakses lokasi Anda. Pastikan GPS aktif.';
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                alert('Browser Anda tidak mendukung Geolocation');
            }
        }

        // Fungsi untuk menampilkan TPS di peta
        // Tambahan pada fungsi showTpsOnMap untuk segera menggambar rute
        function showTpsOnMap(jadwalId) {
            // Hapus marker TPS sebelumnya jika sudah ada
            if (tpsMarkers[jadwalId]) {
                tpsMarkers[jadwalId].forEach(marker => map.removeLayer(marker));
            }

            // Buat array baru untuk menyimpan marker
            tpsMarkers[jadwalId] = [];

            // Dapatkan data TPS untuk jadwal ini
            const tpsPoints = tpsData[jadwalId] || [];

            if (tpsPoints.length === 0) {
                alert('Tidak ada data TPS untuk jadwal ini.');
                return;
            }

            // Buat layer group untuk zoom
            const tpsLayerGroup = L.layerGroup();

            // Tambahkan marker untuk setiap TPS
            tpsPoints.forEach((tps, index) => {
                const tpsMarker = L.marker([tps.latitude, tps.longitude], {
                    icon: L.divIcon({
                        className: 'tps-icon',
                        html: `<div style="line-height: 24px;">${index + 1}</div>`,
                        iconSize: [24, 24]
                    })
                });

                tpsMarker.bindPopup(
                    `<b>TPS ${index + 1}</b><br>${tps.nama}<br>Lat: ${tps.latitude}<br>Lng: ${tps.longitude}`);
                tpsMarker.addTo(map);
                tpsLayerGroup.addLayer(tpsMarker);
                tpsMarkers[jadwalId].push(tpsMarker);
            });

            // Zoom ke semua marker TPS dan posisi saat ini
            const allMarkers = [...tpsMarkers[jadwalId]];
            if (marker) {
                allMarkers.push(marker);
            }
            const tpsGroup = L.featureGroup(allMarkers);
            map.fitBounds(tpsGroup.getBounds(), {
                padding: [50, 50]
            });

            // Tampilkan rute dari posisi saat ini ke semua TPS
            drawRouteBetweenTps(tpsPoints);
        }

        // Fungsi untuk menggambar rute dari lokasi saat ini ke semua TPS
        function drawRouteBetweenTps(tpsPoints) {
            // Hapus routing control sebelumnya jika ada
            if (routeControl) {
                map.removeControl(routeControl);
                routeControl = null;
            }

            // Pastikan ada titik TPS
            if (!tpsPoints || tpsPoints.length === 0) {
                return;
            }

            // Dapatkan posisi saat ini dari marker
            const currentPosition = marker.getLatLng();

            // Buat array waypoints untuk routing, dimulai dari posisi saat ini
            const waypoints = [L.latLng(currentPosition.lat, currentPosition.lng)];

            // Tambahkan titik-titik TPS
            tpsPoints.forEach(tps => {
                waypoints.push(L.latLng(tps.latitude, tps.longitude));
            });

            if (waypoints.length < 2) {
                return; // Minimal butuh 2 titik untuk membuat rute
            }

            // Buat routing control baru dengan opsi yang ditingkatkan
            routeControl = L.Routing.control({
                waypoints: waypoints,
                routeWhileDragging: false,
                showAlternatives: false,
                fitSelectedRoutes: false,
                show: false, // Jangan tampilkan instruksi rute
                lineOptions: {
                    styles: [{
                            color: 'blue',
                            opacity: 0.7,
                            weight: 6
                        },
                        {
                            color: 'white',
                            opacity: 0.5,
                            weight: 2
                        }
                    ]
                },
                createMarker: function() {
                    return null; // Jangan buat marker default dari routing
                },
                router: L.Routing.osrmv1({
                    serviceUrl: 'https://router.project-osrm.org/route/v1',
                    profile: 'driving', // Pilih driving untuk rute jalan
                    useHints: false,
                    geometryOnly: false,
                    suppressDemoServerWarning: true,
                    roundTrip: false,
                    alternatives: false,
                    steps: true,
                    overview: "full",
                    geometries: "polyline"
                })
            }).addTo(map);

            // Tambahkan event handler untuk menangani hasil routing
            routeControl.on('routesfound', function(e) {
                const routes = e.routes;
                const summary = routes[0].summary;

                console.log('Rute ditemukan:', routes);
                console.log('Jarak total:', summary.totalDistance, 'meter');
                console.log('Waktu perkiraan:', summary.totalTime, 'detik');

                // Set flag rute terlihat setelah rute berhasil dibuat
                isRouteVisible = true;
                document.getElementById('toggle-route').textContent = 'Sembunyikan Rute';
            });
        }

        function checkRouteStatus() {
            // Cek apakah ada TPS yang sudah ditampilkan dan routeControl sudah ada
            if (routeControl && isRouteVisible) {
                document.getElementById('toggle-route').textContent = 'Sembunyikan Rute';
            } else {
                document.getElementById('toggle-route').textContent = 'Tampilkan Rute';
            }
        }

        // Modifikasi DOMContentLoaded untuk memanggil checkRouteStatus
        document.addEventListener('DOMContentLoaded', function() {
            initializeMap();
            loadOfflineData();
            setupEventListeners();
            checkActiveTracking();
            checkRouteStatus(); // Tambahkan ini
        });
        // Toggle visibilitas rute (PERBAIKAN)
        function toggleRouteVisibility() {
            // Jika routeControl belum ada, coba buat rute untuk jadwal aktif atau yang pertama
            if (!routeControl) {
                // Cek apakah ada jadwal aktif
                let selectedJadwalId = activeJadwalId;

                // Jika tidak ada jadwal aktif, cari jadwal yang sedang berjalan atau yang pertama
                if (!selectedJadwalId) {
                    // Cari jadwal yang statusnya 1 (sedang berjalan)
                    const activeJadwal = document.querySelector('.jadwal-buttons[data-status="1"]');
                    if (activeJadwal) {
                        selectedJadwalId = activeJadwal.dataset.id;
                    } else {
                        // Jika tidak ada yang aktif, ambil jadwal pertama yang tersedia
                        const firstJadwal = document.querySelector('.jadwal-buttons');
                        if (firstJadwal) {
                            selectedJadwalId = firstJadwal.dataset.id;
                        }
                    }
                }

                // Jika masih tidak ada jadwal yang ditemukan
                if (!selectedJadwalId) {
                    alert('Tidak ada jadwal yang dapat ditampilkan rutenya. Silakan pilih jadwal terlebih dahulu.');
                    return;
                }

                // Cek apakah ada data TPS untuk jadwal ini
                if (!tpsData[selectedJadwalId] || tpsData[selectedJadwalId].length === 0) {
                    alert('Tidak ada data TPS untuk jadwal ini. Silakan tampilkan TPS terlebih dahulu.');
                    return;
                }

                // Tampilkan TPS dan rute untuk jadwal yang dipilih
                showTpsOnMap(selectedJadwalId);

                // Set tombol menjadi "Sembunyikan Rute" setelah rute dibuat
                setTimeout(() => {
                    if (routeControl && isRouteVisible) {
                        document.getElementById('toggle-route').textContent = 'Sembunyikan Rute';
                    }
                }, 500);

                return;
            }

            // Jika routeControl sudah ada, toggle visibility
            if (isRouteVisible) {
                // Sembunyikan rute
                const routingContainer = document.querySelector('.leaflet-routing-container');
                if (routingContainer) {
                    routingContainer.style.display = 'none';
                }

                // Sembunyikan polyline rute
                map.eachLayer(function(layer) {
                    if (layer instanceof L.Polyline && !(layer instanceof L.Marker)) {
                        layer.setStyle({
                            opacity: 0
                        });
                    }
                });

                document.getElementById('toggle-route').textContent = 'Tampilkan Rute';
                isRouteVisible = false;
            } else {
                // Tampilkan rute
                const routingContainer = document.querySelector('.leaflet-routing-container');
                if (routingContainer) {
                    routingContainer.style.display = '';
                }

                // Tampilkan polyline rute
                map.eachLayer(function(layer) {
                    if (layer instanceof L.Polyline && !(layer instanceof L.Marker)) {
                        layer.setStyle({
                            opacity: 0.7
                        });
                    }
                });

                document.getElementById('toggle-route').textContent = 'Sembunyikan Rute';
                isRouteVisible = true;
            }
        }

        // Tampilkan semua TPS untuk semua jadwal
        function showAllTps() {
            // Hapus semua marker TPS yang sudah ada
            Object.values(tpsMarkers).forEach(markers => {
                markers.forEach(marker => map.removeLayer(marker));
            });

            tpsMarkers = {};

            // Kumpulkan semua titik TPS
            const allTpsPoints = [];

            // Iterasi melalui setiap jadwal dan TPS-nya
            Object.entries(tpsData).forEach(([jadwalId, tpsPoints]) => {
                // Buat array baru untuk menyimpan marker untuk jadwal ini
                tpsMarkers[jadwalId] = [];

                // Tambahkan marker untuk setiap TPS
                tpsPoints.forEach((tps, index) => {
                    const tpsMarker = L.marker([tps.latitude, tps.longitude], {
                        icon: L.divIcon({
                            className: 'tps-icon',
                            html: `<div style="line-height: 24px;">${index + 1}</div>`,
                            iconSize: [24, 24]
                        })
                    });

                    tpsMarker.bindPopup(`<b>TPS ${index + 1}</b><br>${tps.nama}<br>Jadwal ID: ${jadwalId}`);
                    tpsMarker.addTo(map);
                    tpsMarkers[jadwalId].push(tpsMarker);

                    // Tambahkan ke array semua titik
                    allTpsPoints.push(tps);
                });
            });

            // Jika ada TPS, zoom ke semua marker
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
            // Hapus semua marker TPS dari peta
            Object.values(tpsMarkers).forEach(markers => {
                markers.forEach(marker => map.removeLayer(marker));
            });

            // Reset objek tpsMarkers
            tpsMarkers = {};

            // Hapus rute jika ada
            if (routeControl) {
                map.removeControl(routeControl);
                routeControl = null;
                isRouteVisible = false;
                document.getElementById('toggle-route').textContent = 'Tampilkan Rute';
            }
        }

        // Menyiapkan event listeners
        function setupEventListeners() {
            // Event listener untuk tombol Mulai Tracking
            document.querySelectorAll('.start-tracking').forEach(button => {
                button.addEventListener('click', function() {
                    const jadwalContainer = this.closest('.jadwal-buttons');
                    const jadwalId = jadwalContainer.dataset.id;
                    const jadwalStatus = jadwalContainer.dataset.status;

                    // Reset counter jika mulai baru
                    if (jadwalStatus === '0') {
                        trackingCounter = 0;
                    }

                    startTracking(jadwalId);
                });
            });

            // Event listener untuk tombol Selesai Tracking
            document.querySelectorAll('.finish-tracking').forEach(button => {
                button.addEventListener('click', function() {
                    const jadwalContainer = this.closest('.jadwal-buttons');
                    const jadwalId = jadwalContainer.dataset.id;
                    finishTracking(jadwalId);
                });
            });

            // Event listener untuk tombol Tampilkan TPS
            document.querySelectorAll('.show-tps-button').forEach(button => {
                button.addEventListener('click', function() {
                    const jadwalId = this.dataset.jadwalId;
                    showTpsOnMap(jadwalId);
                });
            });

            // Event listener untuk Tampilkan Semua TPS
            document.getElementById('show-all-tps').addEventListener('click', showAllTps);

            // Event listener untuk Sembunyikan TPS
            document.getElementById('hide-all-tps').addEventListener('click', hideAllTps);

            // Event listener untuk Toggle Rute
            document.getElementById('toggle-route').addEventListener('click', toggleRouteVisibility);

            // Event untuk saat halaman akan ditutup
            window.addEventListener('beforeunload', function() {
                // Simpan data offline jika ada tracking aktif
                if (activeJadwalId) {
                    saveOfflineData();
                }

                // Hentikan tracking jika sedang berjalan
                stopTracking();
            });
        }

        // Fungsi untuk memulai tracking jadwal tertentu
        function startTracking(jadwalId) {
            // Jika sudah ada jadwal aktif yang berbeda, hentikan dulu
            if (activeJadwalId && activeJadwalId !== jadwalId) {
                stopTracking();
            }

            // Set jadwal aktif
            activeJadwalId = jadwalId;

            // Kirim request ke server untuk update status jadwal
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

                        // Update UI untuk jadwal yang aktif
                        const jadwalContainer = document.querySelector(`.jadwal-buttons[data-id="${jadwalId}"] `);
                        jadwalContainer.dataset.status = '1'; // Update status ke "Sedang Berjalan"

                        // Perbarui tombol
                        updateTrackingButtons(jadwalContainer, true);

                        // Update indikator status tracking
                        document.getElementById('status-indicator').classList.remove('status-inactive');
                        document.getElementById('status-indicator').classList.add('status-active');
                        document.getElementById('tracking-status').textContent = 'Tracking aktif';

                        // Tambahkan elemen counter jika belum ada
                        if (!jadwalContainer.querySelector('.tracking-counter')) {
                            const counterElement = document.createElement('div');
                            counterElement.className = 'tracking-counter mt-2 small text-muted';
                            counterElement.textContent = 'Update ke-0';
                            jadwalContainer.appendChild(counterElement);
                        }

                        // Update referensi ke elemen counter
                        trackingCounterElement = jadwalContainer.querySelector('.tracking-counter');

                        // Mulai tracking GPS
                        startGpsTracking();

                        // Kirim data offline jika ada
                        sendOfflineData();

                        // Tampilkan TPS untuk jadwal ini
                        showTpsOnMap(jadwalId);
                    } else {
                        console.error('Gagal memulai tracking:', data.message);
                        alert('Gagal memulai tracking: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error saat memulai tracking:', error);
                    alert('Terjadi kesalahan saat memulai tracking.');

                    // Jika gagal karena offline, catat status untuk dicoba lagi nanti
                    if (!navigator.onLine) {
                        offlineQueue.push({
                            type: 'start',
                            jadwalId: jadwalId,
                            timestamp: new Date().getTime()
                        });

                        // Simpan ke localStorage
                        saveOfflineData();

                        // Tetap mulai tracking GPS meskipun offline
                        startGpsTracking();

                        // Update UI
                        const jadwalContainer = document.querySelector(`.jadwal-buttons[data-id="${jadwalId}"]`);
                        updateTrackingButtons(jadwalContainer, true);

                        document.getElementById('status-indicator').classList.remove('status-inactive');
                        document
                            .getElementById('status-indicator').classList.add('status-active');
                        document.getElementById(
                            'tracking-status').textContent = 'Tracking aktif (offline mode)';
                    }
                });
        }

        // Fungsi untuk menyelesaikan tracking
        function finishTracking(jadwalId) {
            if (confirm('Apakah Anda yakin ingin menyelesaikan tracking ini?')) {
                // Kirim request ke server
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

                            // Hentikan tracking GPS
                            stopTracking();

                            // Update UI
                            const jadwalContainer = document.querySelector(`.jadwal-buttons[data-id="${jadwalId}"] `);
                            jadwalContainer.dataset.status = '2'; // Update status ke "Selesai"

                            // Ganti tombol
                            jadwalContainer.innerHTML =
                                '<button class="btn btn-secondary btn-sm" disabled>Selesai</button>';

                            // Update indikator status
                            document.getElementById('status-indicator').classList.remove('status-active');
                            document.getElementById('status-indicator').classList.add('status-inactive');
                            document.getElementById('tracking-status').textContent = 'Tracking tidak aktif';

                            // Reset jadwal aktif
                            activeJadwalId = null;
                        } else {
                            console.error('Gagal menyelesaikan tracking:', data.message);
                            alert('Gagal menyelesaikan tracking: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error saat menyelesaikan tracking:', error);
                        alert('Terjadi kesalahan saat menyelesaikan tracking.');

                        // Jika gagal karena offline, catat status untuk dicoba lagi nanti
                        // Jika gagal karena offline, catat status untuk dicoba lagi nanti
                        if (!navigator.onLine) {
                            offlineQueue.push({
                                type: 'finish',
                                jadwalId: jadwalId,
                                timestamp: new Date().getTime()
                            });

                            // Simpan ke localStorage
                            saveOfflineData();

                            // Hentikan tracking GPS meskipun offline
                            stopTracking();

                            // Update UI sebagai sudah selesai
                            const jadwalContainer = document.querySelector(`.jadwal-buttons[data-id="${jadwalId}"]`);
                            jadwalContainer.innerHTML =
                                '<button class="btn btn-secondary btn-sm" disabled>Selesai (offline)</button>';

                            document.getElementById('status-indicator').classList.remove('status-active');
                            document.getElementById('status-indicator').classList.add('status-inactive');
                            document.getElementById('tracking-status').textContent = 'Tracking tidak aktif';

                            // Reset jadwal aktif
                            activeJadwalId = null;
                        }
                    });
            }
        }

        // Fungsi untuk memulai GPS tracking
        function startGpsTracking() {
            // Hentikan interval sebelumnya jika ada
            if (trackingInterval) {
                clearInterval(trackingInterval);
            }

            // Hentikan watch sebelumnya jika ada
            if (watchId) {
                navigator.geolocation.clearWatch(watchId);
            }

            // Mulai tracking GPS dengan interval
            if (navigator.geolocation) {
                // Pertama, dapatkan lokasi langsung
                navigator.geolocation.getCurrentPosition(
                    updateLocationAndSend,
                    handleLocationError, {
                        enableHighAccuracy: true,
                        maximumAge: 0,
                        timeout: 10000
                    }
                );

                // Kemudian setup interval untuk melakukan update setiap 5 detik
                trackingInterval = setInterval(function() {
                    navigator.geolocation.getCurrentPosition(
                        updateLocationAndSend,
                        handleLocationError, {
                            enableHighAccuracy: true,
                            maximumAge: 0,
                            timeout: 10000
                        }
                    );
                }, 5000); // Update setiap 5 detik
            } else {
                alert('Browser Anda tidak mendukung Geolocation');
            }
        }

        // Fungsi untuk mengupdate lokasi dan mengirim ke server
        function updateLocationAndSend(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // Update marker pada peta
            if (marker && map) {
                marker.setLatLng([lat, lng]);

                // Pastikan marker terlihat di dalam view
                if (!map.getBounds().contains(marker.getLatLng())) {
                    map.panTo(marker.getLatLng());
                }

                // Update info lokasi
                document.getElementById('location-info').textContent =
                    `Posisi saat ini: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;

                // Update rute jika rute sedang ditampilkan dan ada jadwal aktif
                if (isRouteVisible && activeJadwalId && tpsData[activeJadwalId]) {
                    // Perbarui rute dengan posisi terbaru
                    drawRouteBetweenTps(tpsData[activeJadwalId]);
                }
            }

            // Kirim data ke server jika ada jadwal aktif
            if (activeJadwalId) {
                sendLocationToServer(lat, lng);

                // Update counter dan tampilkan
                trackingCounter++;
                if (trackingCounterElement) {
                    trackingCounterElement.textContent = `Update ke-${trackingCounter}`;
                }

                // Perbarui status TPS jika dekat dengan lokasi saat ini
                updateTpsStatusBasedOnLocation(lat, lng);
            }
        }

        // Fungsi untuk memperbarui status TPS berdasarkan kedekatan dengan lokasi saat ini
        function updateTpsStatusBasedOnLocation(lat, lng) {
            if (!activeJadwalId || !tpsMarkers[activeJadwalId]) return;

            const tpsPoints = tpsData[activeJadwalId] || [];
            const currentLatLng = L.latLng(lat, lng);

            // Jarak maksimum dalam meter untuk menandai TPS sebagai dikunjungi
            const THRESHOLD_DISTANCE = 50; // meter

            tpsPoints.forEach((tps, index) => {
                const tpsLatLng = L.latLng(tps.latitude, tps.longitude);
                const distance = currentLatLng.distanceTo(tpsLatLng);

                if (distance <= THRESHOLD_DISTANCE) {
                    // Jika jarak dekat, ubah status TPS menjadi aktif/dikunjungi
                    const tpsMarker = tpsMarkers[activeJadwalId][index];

                    // Hanya perbarui jika marker ada dan belum ditandai aktif
                    if (tpsMarker && !tpsMarker.options.icon.options.className.includes('marker-active') &&
                        !tpsMarker.options.icon.options.className.includes('marker-completed')) {

                        // Hapus marker lama dan buat marker baru dengan ikon aktif
                        map.removeLayer(tpsMarker);

                        const newMarker = L.marker([tps.latitude, tps.longitude], {
                            icon: L.divIcon({
                                className: 'tps-icon marker-active',
                                html: `<div style="line-height: 24px;">${index + 1}</div>`,
                                iconSize: [24, 24]
                            })
                        });

                        newMarker.bindPopup(`<b>TPS ${index + 1}</b><br>${tps.nama}<br>Status: Aktif/Dikunjungi`);
                        newMarker.addTo(map);

                        // Ganti marker lama dengan marker baru
                        tpsMarkers[activeJadwalId][index] = newMarker;

                        // Buka popup untuk memberi tahu pengguna
                        newMarker.openPopup();

                        // Optional: Mainkan suara atau tampilkan notifikasi
                        // playNotificationSound();
                    }
                }
            });
        }

        // Mengirim lokasi ke server
        function sendLocationToServer(lat, lng) {
            // Data untuk dikirim
            const locationData = {
                id_jadwal_operasional: activeJadwalId,
                latitude: lat,
                longitude: lng
            };

            // Kirim ke server
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

                    // Jika offline, simpan data untuk dikirim nanti
                    if (!navigator.onLine) {
                        offlineQueue.push({
                            type: 'location',
                            data: locationData,
                            timestamp: new Date().getTime()
                        });

                        // Simpan ke localStorage
                        saveOfflineData();
                    }
                });
        }

        // Hentikan tracking
        function stopTracking() {
            // Hentikan interval tracking
            if (trackingInterval) {
                clearInterval(trackingInterval);
                trackingInterval = null;
            }

            // Hentikan watch position
            if (watchId) {
                navigator.geolocation.clearWatch(watchId);
                watchId = null;
            }

            // Reset counter
            trackingCounter = 0;
            trackingCounterElement = null;

            // Reset jadwal aktif
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
                        <button class="btn btn-primary btn-sm start-tracking active">Tracking Aktif</button>
                        <button class="btn btn-danger btn-sm finish-tracking">Selesai</button>
                        <div class="tracking-counter mt-2 small text-muted">Update ke-${trackingCounter}</div>
                    `;

                // Pasang event listener untuk tombol selesai
                container.querySelector('.finish-tracking').addEventListener('click', function() {
                    const jadwalId = container.dataset.id;
                    finishTracking(jadwalId);
                });
            } else {
                if (container.dataset.status === '0') {
                    container.innerHTML = '<button class="btn btn-success btn-sm start-tracking">Mulai Tracking</button>';

                    // Pasang event listener untuk tombol mulai
                    container.querySelector('.start-tracking').addEventListener('click', function() {
                        const jadwalId = container.dataset.id;
                        startTracking(jadwalId);
                    });
                } else if (container.dataset.status === '2') {
                    container.innerHTML = '<button class="btn btn-secondary btn-sm" disabled>Selesai</button>';
                }
            }
        }

        // Cek jadwal yang sudah aktif
        function checkActiveTracking() {
            document.querySelectorAll('.jadwal-buttons').forEach(container => {
                const jadwalStatus = container.dataset.status;

                if (jadwalStatus === '1') { // Status "Sedang Berjalan"
                    const jadwalId = container.dataset.id;
                    activeJadwalId = jadwalId;

                    // Mulai tracking GPS
                    startGpsTracking();

                    // Update UI
                    updateTrackingButtons(container, true);

                    // Update indikator status
                    document.getElementById('status-indicator').classList.remove('status-inactive');
                    document.getElementById('status-indicator').classList.add('status-active');
                    document.getElementById('tracking-status').textContent = 'Tracking aktif';

                    // Update referensi ke elemen counter
                    trackingCounterElement = container.querySelector('.tracking-counter');

                    // Tampilkan TPS untuk jadwal ini
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

            // Proses data offline
            const promises = [];

            offlineQueue.forEach(item => {
                let promise;

                if (item.type === 'location') {
                    // Kirim data lokasi
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
                    // Kirim request start
                    promise = fetch(`/petugas/jadwal-pengambilan/${item.jadwalId}/start`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });
                } else if (item.type === 'finish') {
                    // Kirim request finish
                    promise = fetch(`/petugas/jadwal-pengambilan/${item.jadwalId}/finish`, {
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

            // Jika semua request berhasil, kosongkan queue
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

            // Kirim data offline
            sendOfflineData();
        });

        window.addEventListener('offline', function() {
            console.log('Koneksi offline');
            if (activeJadwalId) {
                document.getElementById('tracking-status').textContent = 'Tracking aktif (offline mode)';
            }
        });


        // Fungsi untuk pelacakan lokasi yang ditingkatkan dengan compass surrogate
        function enhancedLocationTracking() {
            // Variabel untuk menyimpan history lokasi
            let locationHistory = [];
            // Variabel untuk menyimpan arah (bearing)
            let currentBearing = 0;
            // Kecepatan minimum untuk menghitung arah yang valid (dalam m/s)
            const MIN_SPEED_FOR_BEARING = 1.5;

            // Fungsi untuk memulai tracking dengan dukungan arah (compass surrogate)
            function startEnhancedTracking() {
                if (trackingInterval) {
                    clearInterval(trackingInterval);
                }

                if (watchId) {
                    navigator.geolocation.clearWatch(watchId);
                }

                // Reset history lokasi
                locationHistory = [];

                // Mulai tracking dengan opsi yang ditingkatkan
                if (navigator.geolocation) {
                    // Dapatkan posisi awal
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            // Simpan posisi awal ke history
                            const initialPosition = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                                timestamp: position.timestamp,
                                accuracy: position.coords.accuracy,
                                speed: position.coords.speed || 0
                            };
                            locationHistory.push(initialPosition);

                            // Update lokasi dan kirim ke server
                            updateLocationAndSendEnhanced(position);
                        },
                        handleLocationError, {
                            enableHighAccuracy: true,
                            maximumAge: 0,
                            timeout: 10000
                        }
                    );

                    // Lakukan polling lokasi dengan interval untuk mendapatkan data lebih sering
                    trackingInterval = setInterval(function() {
                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                updateLocationAndSendEnhanced(position);
                            },
                            handleLocationError, {
                                enableHighAccuracy: true,
                                maximumAge: 0,
                                timeout: 10000
                            }
                        );
                    }, 5000); // Polling setiap 5 detik
                } else {
                    alert('Browser Anda tidak mendukung Geolocation');
                }
            }

            // Fungsi untuk menghitung bearing (arah) berdasarkan 2 titik koordinat
            function calculateBearing(startLat, startLng, endLat, endLng) {
                startLat = toRadians(startLat);
                startLng = toRadians(startLng);
                endLat = toRadians(endLat);
                endLng = toRadians(endLng);

                const y = Math.sin(endLng - startLng) * Math.cos(endLat);
                const x = Math.cos(startLat) * Math.sin(endLat) -
                    Math.sin(startLat) * Math.cos(endLat) * Math.cos(endLng - startLng);
                let bearing = Math.atan2(y, x);

                // Konversi dari radian ke derajat
                bearing = toDegrees(bearing);

                // Normalisasi bearing ke 0-360
                return (bearing + 360) % 360;
            }

            // Konversi derajat ke radian
            function toRadians(degrees) {
                return degrees * (Math.PI / 180);
            }

            // Konversi radian ke derajat
            function toDegrees(radians) {
                return radians * (180 / Math.PI);
            }

            // Fungsi untuk menghaluskan data lokasi dan menghitung arah
            function updateLocationAndSendEnhanced(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const accuracy = position.coords.accuracy;
                const speed = position.coords.speed || 0; // m/s
                const timestamp = position.timestamp;

                // Filter lokasi berdasarkan akurasi
                if (accuracy > 100) { // Jika akurasi lebih dari 50 meter, abaikan
                    console.log(`Mengabaikan lokasi dengan akurasi rendah: ${accuracy}m`);
                    document.getElementById('location-info').textContent =
                        `Mencari sinyal GPS yang lebih akurat... (${accuracy.toFixed(1)}m)`;
                    return;
                }

                // Tambahkan ke history lokasi
                locationHistory.push({
                    lat,
                    lng,
                    timestamp,
                    accuracy,
                    speed
                });

                // Batasi jumlah history yang disimpan
                if (locationHistory.length > 5) {
                    locationHistory.shift();
                }

                // Hitung bearing (arah) jika ada minimal 2 titik dan kecepatan cukup
                if (locationHistory.length >= 2 && speed >= MIN_SPEED_FOR_BEARING) {
                    const prevLocation = locationHistory[locationHistory.length - 2];

                    // Hitung bearing saat ini
                    const newBearing = calculateBearing(
                        prevLocation.lat, prevLocation.lng,
                        lat, lng
                    );

                    // Update bearing saat ini dengan smoothing
                    if (Math.abs(newBearing - currentBearing) < 180) {
                        // Smoothing sederhana untuk menghindari lompatan
                        currentBearing = currentBearing * 0.7 + newBearing * 0.3;
                    } else {
                        // Jika ada lompatan besar (misalnya dari 359° ke 1°), gunakan nilai baru
                        currentBearing = newBearing;
                    }
                }

                // Update marker pada peta
                if (marker && map) {
                    marker.setLatLng([lat, lng]);

                    // Update rotasi marker berdasarkan bearing jika kecepatan cukup
                    if (speed >= MIN_SPEED_FOR_BEARING) {
                        // Gunakan custom icon dengan rotasi
                        const markerIcon = L.divIcon({
                            className: 'tps-icon',
                            html: `<div style="transform: rotate(${currentBearing}deg); line-height: 30px;"><i class="fas fa-truck"></i></div>`,
                            iconSize: [30, 30]
                        });
                        marker.setIcon(markerIcon);
                    }

                    // Pastikan marker terlihat di dalam view
                    if (!map.getBounds().contains(marker.getLatLng())) {
                        map.panTo(marker.getLatLng());
                    }

                    // Update info lokasi dengan informasi tambahan
                    document.getElementById('location-info').textContent =
                        `Posisi: ${lat.toFixed(6)}, ${lng.toFixed(6)} | Akurasi: ${accuracy.toFixed(1)}m | Arah: ${currentBearing.toFixed(0)}° | Kecepatan: ${(speed * 3.6).toFixed(1)} km/h`;

                    // Update rute jika rute sedang ditampilkan dan ada jadwal aktif
                    if (isRouteVisible && activeJadwalId && tpsData[activeJadwalId]) {
                        // Perbarui rute dengan posisi terbaru hanya jika lokasi cukup berubah
                        if (locationHistory.length >= 2) {
                            const prevLocation = locationHistory[locationHistory.length - 2];
                            const distance = L.latLng(prevLocation.lat, prevLocation.lng).distanceTo(L.latLng(lat, lng));

                            // Update rute hanya jika berpindah minimal 20 meter
                            if (distance > 20) {
                                drawRouteBetweenTps(tpsData[activeJadwalId]);
                            }
                        }
                    }
                }

                // Kirim data ke server jika ada jadwal aktif
                if (activeJadwalId) {
                    // Tambahkan bearing dan kecepatan ke data yang dikirim
                    sendEnhancedLocationToServer(lat, lng, accuracy, currentBearing, speed);

                    // Update counter dan tampilkan
                    trackingCounter++;
                    if (trackingCounterElement) {
                        trackingCounterElement.textContent = `Update ke-${trackingCounter}`;
                    }

                    // Perbarui status TPS jika dekat dengan lokasi saat ini
                    updateTpsStatusBasedOnLocation(lat, lng);
                }
            }

            // Fungsi untuk mengirim data lokasi yang ditingkatkan ke server
            function sendEnhancedLocationToServer(lat, lng, accuracy, bearing, speed) {
                // Data untuk dikirim dengan informasi tambahan
                const locationData = {
                    id_jadwal_operasional: activeJadwalId,
                    latitude: lat,
                    longitude: lng,
                    accuracy: accuracy, // akurasi dalam meter
                    bearing: bearing, // arah dalam derajat (0-360)
                    speed: speed // kecepatan dalam m/s
                };

                // Kirim ke server
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

                        // Jika offline, simpan data untuk dikirim nanti
                        if (!navigator.onLine) {
                            offlineQueue.push({
                                type: 'location',
                                data: locationData,
                                timestamp: new Date().getTime()
                            });

                            // Simpan ke localStorage
                            saveOfflineData();
                        }
                    });
            }

            // Return fungsi yang diekspos ke luar
            return {
                startEnhancedTracking: startEnhancedTracking,
                // Fungsi tambahan lainnya yang mungkin perlu diekspos
            };
        }

        // Inisialisasi dan ekspos fungsi ke global scope
        const locationTracker = enhancedLocationTracking();

        // Ganti fungsi startGpsTracking asli dengan versi yang ditingkatkan
        function startGpsTracking() {
            // Gunakan fungsi yang ditingkatkan
            locationTracker.startEnhancedTracking();
        }
    </script>
@endpush
