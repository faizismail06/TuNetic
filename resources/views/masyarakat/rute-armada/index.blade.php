@extends('components.navbar')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <!-- Leaflet Routing Machine CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        #map {
            height: 500px;
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

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-belum {
            background-color: #e9ecef;
            color: #6c757d;
        }

        .status-sedang {
            background-color: #d4edda;
            color: #155724;
            animation: pulse 1.5s infinite;
        }

        .status-selesai {
            background-color: #cce5ff;
            color: #004085;
        }

        .jadwal-card {
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .jadwal-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .day-pill {
            display: inline-block;
            padding: 6px 12px;
            margin: 2px;
            border-radius: 20px;
            text-decoration: none;
            color: #6c757d;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .day-pill:hover {
            background-color: #e9ecef;
            color: #495057;
        }

        .day-pill.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .info-box {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
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

        /* Popup Styling */
        .leaflet-popup-content-wrapper {
            border-radius: 8px;
            padding: 0;
        }

        .popup-header {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            font-weight: bold;
        }

        .popup-content {
            padding: 10px 15px;
        }

        .popup-footer {
            padding: 8px 15px;
            border-top: 1px solid #dee2e6;
            text-align: center;
        }

        /* Custom Modal */
        .jadwal-detail-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 2000;
            display: none;
        }

        .jadwal-detail-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1999;
            display: none;
        }

        .jadwal-popup-header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            position: relative;
        }

        .jadwal-popup-close {
            position: absolute;
            top: 15px;
            right: 15px;
            color: white;
            cursor: pointer;
            font-size: 20px;
        }

        .jadwal-popup-body {
            padding: 20px;
        }

        .custom-popup .leaflet-popup-content {
            max-height: 300px;
            /* Tinggi maksimum */
            min-height: 150px;
            /* Tinggi minimum */
            overflow-y: auto;
            /* Tambahkan scrollbar jika konten melebihi max-height */
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
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h1 class="mb-0 fs-4 fw-bold d-flex align-items-center">
                            <i class="fas fa-route me-2"></i>
                            Rute Armada Sampah - {{ $selectedDayInfo }}
                        </h1>
                    </div>

                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <!-- Informasi Umum -->
                        <div class="info-box">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="fw-bold mb-2">Informasi:</h6>
                                    <ul class="mb-0">
                                        <li>Jadwal operasional armada sampah untuk hari
                                            <strong>{{ $selectedDayInfo }}</strong>
                                        </li>
                                        <li>{{ count($jadwalOperasional) }} armada dijadwalkan beroperasi</li>
                                        <li>Klik pada marker untuk melihat detail</li>
                                    </ul>
                                </div>
                                <div class="col-md-4 text-end">
                                    <a href="{{ route('masyarakat.rute-armada.all-tps') }}" class="btn btn-success">
                                        <i class="fas fa-map-marked-alt me-1"></i>
                                        Lihat Semua TPS
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Peta -->
                        <div class="map-card mb-4">
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Jadwal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Jadwal Operasional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalDetailContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popup Detail Armada -->
    <div class="jadwal-detail-popup" id="jadwalDetailPopup">
        <div class="jadwal-popup-header">
            <h5 class="mb-0">Detail Jadwal Operasional</h5>
            <span class="jadwal-popup-close" onclick="closeJadwalPopup()">&times;</span>
        </div>
        <div class="jadwal-popup-body" id="jadwalDetailContent">
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
    <div class="jadwal-detail-backdrop" id="jadwalDetailBackdrop" onclick="closeJadwalPopup()"></div>

    <!-- Data untuk JavaScript -->
    <script>
        const jadwalData = @json($jadwalOperasional);
        const allTps = @json($allTps);
        const routeData = @json($routeData);
        const armadaData = @json($armadaData);
    </script>
@endsection

@push('js')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <!-- Leaflet Routing Machine -->
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <script>
        // Variabel global
        let map;
        let markersLayer = L.layerGroup();
        let routesLayer = L.layerGroup();
        let detailModal;

        // Inisialisasi peta - menggunakan pendekatan yang lebih sederhana dan handal seperti di kode 2
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded');

            // Cek apakah div map ada
            const mapElement = document.getElementById('map');
            if (!mapElement) {
                console.error('Map element not found!');
                return;
            }

            // Pastikan data sudah dimuat
            if (!jadwalData || !allTps || !routeData || !armadaData) {
                console.error('Data not loaded properly');
                return;
            }

            // Debug data
            console.log('TPS Data:', allTps);
            console.log('Route Data:', routeData);

            // Inisialisasi modal
            detailModal = new bootstrap.Modal(document.getElementById('detailModal'));

            // Inisialisasi peta - lebih sederhana seperti di kode 2
            initializeMap();

            // Setup event listeners untuk interaksi pengguna
            setupEventListeners();

            // Menampilkan semua rute segera setelah inisialisasi peta
            showAllRoutes();
        });

        try {
            // Variabel global untuk menyimpan referensi peta
            let map = null;

            // Fungsi untuk inisialisasi peta dengan koordinat tertentu
            function initializeMap(lat, lng, zoom = 15) {
                // Validasi koordinat
                if (!lat || !lng || isNaN(lat) || isNaN(lng)) {
                    console.error('Koordinat tidak valid:', lat, lng);
                    return;
                }

                // Hapus peta yang sudah ada jika ada
                if (map) {
                    map.remove();
                    map = null;
                }

                // Inisialisasi peta baru
                map = L.map('map').setView([lat, lng], zoom);

                // Tambahkan tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // Tambahkan marker pada lokasi pengguna
                // L.marker([lat, lng])
                //     .addTo(map)
                //     .bindPopup('Lokasi Anda')
                //     .openPopup();
            }

            // Cek apakah browser mendukung Geolocation
            if (navigator.geolocation) {
                // Tampilkan loading atau pesan sementara
                console.log("Mengambil lokasi Anda...");

                // Opsi untuk geolocation
                const options = {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 60000
                };

                // Ambil lokasi pengguna
                navigator.geolocation.getCurrentPosition(
                    // Success callback
                    function(position) {
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;

                        console.log(`Lokasi ditemukan: ${userLat}, ${userLng}`);

                        // Validasi koordinat sebelum inisialisasi
                        if (userLat && userLng && !isNaN(userLat) && !isNaN(userLng)) {
                            initializeMap(userLat, userLng, 15);
                        } else {
                            console.error('Koordinat tidak valid dari geolocation');
                            initializeMap(-7.056325, 110.454250, 12);
                        }
                    },
                    // Error callback
                    function(error) {
                        console.warn('Error mendapatkan lokasi:', error.message);

                        // Fallback ke lokasi default Jawa Tengah jika gagal
                        console.log('Menggunakan lokasi default: Jawa Tengah');
                        initializeMap(-7.056325, 110.454250, 12);

                        // Tampilkan pesan error kepada pengguna (opsional)
                        alert('Tidak dapat mengakses lokasi Anda. Menggunakan lokasi default.');
                    },
                    options
                );
            } else {
                // Browser tidak mendukung Geolocation
                console.warn('Browser tidak mendukung Geolocation');

                // Gunakan lokasi default
                initializeMap(-7.056325, 110.454250, 12);
                alert('Browser Anda tidak mendukung deteksi lokasi. Menggunakan lokasi default.');
            }

        } catch (error) {
            console.error('Error inisialisasi peta:', error);

            // Fallback terakhir - pastikan tidak ada peta yang sudah diinisialisasi
            if (map) {
                map.remove();
            }

            map = L.map('map').setView([-7.056325, 110.454250], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
        }

        // Tambahkan legenda ke peta
        function addMapLegend() {
            const legend = L.control({
                position: 'bottomright'
            });

            legend.onAdd = function(map) {
                const div = L.DomUtil.create('div', 'map-legend');
                div.innerHTML = `
        <h6 class="fw-bold mb-2">Legenda</h6>
        <div class="legend-item">
            <div class="legend-icon" style="background-color: #3388ff;"></div>
            <span>TPS</span>
        </div>
        <div class="legend-item">
            <i class="fas fa-truck truck-icon" style="color: #000;"></i>
            <span>Posisi Armada</span>
        </div>
        <div class="legend-item">
            <div style="width: 20px; height: 3px; background-color: blue; margin-right: 8px;"></div>
            <span>Rute Armada</span>
        </div>
    `;
                return div;
            };

            legend.addTo(map);
        }

        // Tampilkan semua rute - disederhanakan
        function showAllRoutes() {
            console.log('Showing all routes');

            // Pastikan map sudah terinisialisasi
            if (!map) {
                console.error('Map not properly initialized');
                return;
            }

            // Clear layers
            markersLayer.clearLayers();
            routesLayer.clearLayers();

            if (!allTps || Object.keys(allTps).length === 0) {
                console.log('No TPS data found');
                return;
            }

            // Simpan semua waypoints untuk fit bounds
            let allMarkers = [];

            // Tambahkan marker dan rute untuk setiap jadwal
            Object.keys(allTps).forEach(jadwalId => {
                const tpsPoints = allTps[jadwalId];
                if (!tpsPoints || tpsPoints.length === 0) {
                    console.log(`No TPS points for jadwal ${jadwalId}`);
                    return;
                }

                const color = routeData[jadwalId].warna;
                const nopol = armadaData[jadwalId].no_polisi;

                console.log(`Adding markers for jadwal ${jadwalId}`, tpsPoints);

                // Tambahkan marker TPS
                tpsPoints.forEach((tps, index) => {
                    if (!tps.latitude || !tps.longitude) {
                        console.error(`Invalid coordinates for TPS ${index} in jadwal ${jadwalId}`, tps);
                        return;
                    }

                    try {
                        // Gunakan ikon yang konsisten dengan halaman all-tps
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
                        const tpsType = tps.jenis ? tps.jenis.toUpperCase() : 'TPS';
                        const tpsConfig = config[tpsType] || config['TPS'];

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

                        marker.bindPopup(`
                <div class="p-2">
                    <h6 class="fw-bold">${tps.nama}</h6>
                    <p class="mb-1">Armada: ${nopol}</p>
                    <p class="mb-1">Urutan: ke-${index + 1}</p>
                    <p class="mb-0">Status: ${tps.status}</p>
                </div>
                `);

                        markersLayer.addLayer(marker);
                        allMarkers.push(marker);
                    } catch (e) {
                        console.error(`Error adding marker for TPS ${index} in jadwal ${jadwalId}:`, e);
                    }
                });

                // Tambahkan rute - Dengan pendekatan polyline terlebih dahulu seperti di kode 2
                if (tpsPoints.length > 1) {
                    console.log(`Adding route for jadwal ${jadwalId}`);

                    try {
                        const waypoints = tpsPoints
                            .filter(tps => tps.latitude && tps.longitude)
                            .map(tps => L.latLng(tps.latitude, tps.longitude));

                        if (waypoints.length < 2) {
                            console.error(`Not enough valid waypoints for jadwal ${jadwalId}`);
                            return;
                        }

                        // Gunakan polyline dulu sebagai baseline yang pasti berhasil
                        const polyline = L.polyline(waypoints, {
                            color: color,
                            weight: 3,
                            opacity: 0.7
                        });
                        routesLayer.addLayer(polyline);

                        // Kemudian coba routing machine sebagai tambahan
                        try {
                            const routingControl = L.Routing.control({
                                waypoints: waypoints,
                                routeWhileDragging: false,
                                showAlternatives: false,
                                fitSelectedRoutes: false,
                                createMarker: function() {
                                    return null;
                                },
                                lineOptions: {
                                    styles: [{
                                        color: color,
                                        weight: 3,
                                        opacity: 0.7
                                    }]
                                },
                                router: L.Routing.osrmv1({
                                    serviceUrl: 'https://router.project-osrm.org/route/v1',
                                    profile: 'driving'
                                }),
                                show: false
                            });

                            routingControl.addTo(routesLayer);
                        } catch (err) {
                            console.log('Routing failed, using polyline as fallback');
                            // Polyline sudah ditambahkan sebelumnya
                        }
                    } catch (e) {
                        console.error(`Error adding route for jadwal ${jadwalId}:`, e);
                    }
                }

                // Tambahkan marker armada jika ada lokasi terakhir
                if (armadaData[jadwalId].last_location) {
                    const loc = armadaData[jadwalId].last_location;
                    try {
                        // Marker truk dengan popup yang lebih informatif
                        const truckMarker = L.marker([loc.latitude, loc.longitude], {
                            icon: L.divIcon({
                                className: 'truck-marker',
                                html: `<div style="position: relative;">
                            <img src="/assets/images/img_truck.png" style="width: 24px; height: auto;">
                        </div>`,
                                iconSize: [30, 30]
                            })
                        });

                        truckMarker.bindPopup(`
                            <div style="height: 250px; overflow-y: auto;">
                                <div class="popup-header" style="background-color: ${routeData[jadwalId].warna}; margin-top: 25px;">
                                    <i class="fas fa-truck me-2"></i> ${nopol}
                                </div>
                                <div class="popup-content">
                                    <p class="mb-1">
                                        <i class="fas fa-route text-muted me-1"></i>
                                        <span class="fw-semibold">Rute:</span>
                                        ${routeData[jadwalId].nama_rute}
                                    </p>
                                    <p class="mb-1">
                                        <i class="fas fa-truck text-muted me-1"></i>
                                        <span class="fw-semibold">Armada:</span>
                                        ${armadaData[jadwalId].jenis}
                                    </p>
                                    <p class="mb-1">
                                        <span class="status-badge status-${['belum', 'sedang', 'selesai'][armadaData[jadwalId].status]}">
                                            ${['Belum Berjalan', 'Sedang Berjalan', 'Selesai'][armadaData[jadwalId].status]}
                                        </span>
                                    </p>
                                </div>
                                <div class="popup-footer">
                                    <button class="btn btn-sm btn-primary w-100" onclick="showJadwalDetail('${jadwalId}')">
                                        <i class="fas fa-info-circle me-1"></i> Detail Armada
                                    </button>
                                </div>
                            </div>
                        `, {
                            maxWidth: 300,
                            minWidth: 250,
                            className: 'custom-popup',
                            closeButton: false // Menonaktifkan tombol close default
                        });

                        markersLayer.addLayer(truckMarker);
                        allMarkers.push(truckMarker);
                    } catch (e) {
                        console.error(`Error adding truck marker for jadwal ${jadwalId}:`, e);
                    }
                }
            });

            // Fit bounds jika ada marker - Menggunakan featureGroup langsung seperti di kode 2
            if (allMarkers.length > 0) {
                try {
                    const group = L.featureGroup(allMarkers);
                    map.fitBounds(group.getBounds().pad(0.1));
                    console.log('Map bounds set');
                } catch (e) {
                    console.error('Error setting map bounds:', e);
                    // Fallback: gunakan view default
                    map.setView([-7.056325, 110.454250], 12);
                }
            } else {
                console.log('No markers to set bounds');
                // Fallback: gunakan view default
                map.setView([-7.056325, 110.454250], 12);
            }
        }

        // Tampilkan jadwal tertentu di peta - Menggunakan pendekatan yang lebih sederhana
        function showJadwalOnMap(jadwalId) {
            // Pastikan map sudah terinisialisasi
            if (!map) {
                console.error('Map not properly initialized');
                return;
            }

            // Clear layers
            markersLayer.clearLayers();
            routesLayer.clearLayers();

            const tpsPoints = allTps[jadwalId];
            if (!tpsPoints || tpsPoints.length === 0) {
                console.log(`No TPS points for jadwal ${jadwalId}`);
                return;
            }

            const color = routeData[jadwalId].warna;
            const nopol = armadaData[jadwalId].no_polisi;

            // Simpan semua marker untuk fit bounds
            let jadwalMarkers = [];

            // Tambahkan marker TPS
            tpsPoints.forEach((tps, index) => {
                if (!tps.latitude || !tps.longitude) {
                    console.error(`Invalid coordinates for TPS ${index} in jadwal ${jadwalId}`, tps);
                    return;
                }

                const marker = L.marker([tps.latitude, tps.longitude], {
                    icon: L.divIcon({
                        className: 'tps-icon',
                        html: `<div style="background-color: ${color}; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; box-shadow: 0 1px 5px rgba(0,0,0,0.4);">${index + 1}</div>`,
                        iconSize: [30, 30]
                    })
                });

                marker.bindPopup(`
        <div class="p-2">
            <h6 class="fw-bold">${tps.nama}</h6>
            <p class="mb-1">Armada: ${nopol}</p>
            <p class="mb-1">Urutan: ke-${index + 1}</p>
            <p class="mb-0">Status: ${tps.status}</p>
        </div>
    `);

                markersLayer.addLayer(marker);
                jadwalMarkers.push(marker);
            });

            // Tambahkan rute - Menggunakan polyline terlebih dahulu seperti di kode 2
            if (tpsPoints.length > 1) {
                const waypoints = tpsPoints
                    .filter(tps => tps.latitude && tps.longitude)
                    .map(tps => L.latLng(tps.latitude, tps.longitude));

                if (waypoints.length < 2) {
                    console.error(`Not enough valid waypoints for jadwal ${jadwalId}`);
                } else {
                    try {
                        // Coba gunakan polyline terlebih dahulu sebagai alternatif yang lebih stabil
                        const polyline = L.polyline(waypoints, {
                            color: color,
                            weight: 4,
                            opacity: 0.8
                        });
                        routesLayer.addLayer(polyline);

                        // Opsional: coba routing jika polyline berhasil dibuat
                        try {
                            const routingControl = L.Routing.control({
                                waypoints: waypoints,
                                routeWhileDragging: false,
                                showAlternatives: false,
                                fitSelectedRoutes: false,
                                createMarker: function() {
                                    return null;
                                },
                                lineOptions: {
                                    styles: [{
                                        color: color,
                                        weight: 4,
                                        opacity: 0.8
                                    }]
                                },
                                router: L.Routing.osrmv1({
                                    serviceUrl: 'https://router.project-osrm.org/route/v1',
                                    profile: 'driving'
                                }),
                                show: false
                            });

                            routingControl.addTo(routesLayer);
                        } catch (err) {
                            console.error('Error adding routing control to layer:', err);
                            // Polyline sudah ditambahkan sebelumnya sebagai fallback
                        }
                    } catch (e) {
                        console.error(`Error adding routing control for jadwal ${jadwalId}:`, e);
                    }
                }
            }

            // Tambahkan marker armada
            if (armadaData[jadwalId].last_location) {
                const loc = armadaData[jadwalId].last_location;
                try {
                    // Ganti kode marker truk dengan img_truck
                    const truckMarker = L.marker([loc.latitude, loc.longitude], {
                        icon: L.divIcon({
                            className: 'truck-marker',
                            html: `<div style="position: relative;">
                        <img src="/assets/images/img_truck.png" style="width: 60px; height: auto;">
                    </div>`,
                            iconSize: [66, 66],
                            iconAnchor: [33, 33]
                        })
                    });

                    // Tambahkan popup dengan tombol detail
                    truckMarker.bindPopup(`
                <div class="p-3">
                    <h6 class="fw-bold">${nopol}</h6>
                    <p class="mb-1">Status: ${['Belum', 'Sedang Berjalan', 'Selesai'][armadaData[jadwalId].status]}</p>
                    <p class="mb-1">Terakhir update: ${new Date(loc.timestamp).toLocaleString('id-ID')}</p>
                    <button class="btn btn-sm btn-primary w-100 mt-2 show-armada-detail"
                        data-jadwal-id="${jadwalId}">
                        <i class="fas fa-info-circle me-1"></i> Detail Armada
                    </button>
                </div>
            `);

                    // Tambahkan event listener saat popup terbuka
                    truckMarker.on('popupopen', function(e) {
                        setTimeout(() => {
                            // Cari tombol detail di popup yang baru dibuka
                            const detailBtn = document.querySelector('.show-armada-detail');
                            if (detailBtn) {
                                // Tambahkan event listener ke tombol
                                detailBtn.addEventListener('click', function() {
                                    const jadwalId = this.dataset.jadwalId;
                                    showArmadaDetail(jadwalId);
                                });
                            }
                        }, 100);
                    });

                    markersLayer.addLayer(truckMarker);
                    jadwalMarkers.push(truckMarker);
                } catch (e) {
                    console.error(`Error adding truck marker for jadwal ${jadwalId}:`, e);
                }
            }

            // Fit bounds untuk jadwal ini
            if (jadwalMarkers.length > 0) {
                const group = L.featureGroup(jadwalMarkers);
                map.fitBounds(group.getBounds().pad(0.1));
            }
        }

        // Auto refresh lokasi armada setiap 30 detik
        setInterval(function() {
            if (armadaData && Object.keys(armadaData).length > 0) {
                const activeJadwal = Object.keys(armadaData).filter(id => armadaData[id].status === 1);

                activeJadwal.forEach(jadwalId => {
                    fetch(`/masyarakat/rute-armada/tracking/${jadwalId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update lokasi armada di peta
                                armadaData[jadwalId].last_location = data.data;
                                showAllRoutes();
                            }
                        })
                        .catch(error => console.error('Error fetching tracking:', error));
                });
            }
        }, 30000);

        // Setup event listeners
        function setupEventListeners() {
            // Event listener untuk tombol detail
            document.querySelectorAll('.show-detail').forEach(button => {
                button.addEventListener('click', function() {
                    const jadwalId = this.dataset.jadwalId;
                    showJadwalDetail(jadwalId);
                });
            });

            // Event listener untuk card jadwal
            document.querySelectorAll('.jadwal-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    // Jika klik tidak pada tombol detail, tampilkan di peta
                    if (!e.target.closest('.show-detail')) {
                        const jadwalId = this.dataset.jadwalId;
                        showJadwalOnMap(jadwalId);

                        // Highlight card yang dipilih
                        document.querySelectorAll('.jadwal-card').forEach(c => {
                            c.style.boxShadow = '0 2px 5px rgba(0, 0, 0, 0.05)';
                            c.style.transform = 'none';
                        });
                        this.style.boxShadow = '0 0 15px rgba(0, 123, 255, 0.5)';
                        this.style.transform = 'translateY(-2px)';
                    }
                });
            });
        } // Variabel global

        // Tampilkan detail jadwal
        function showJadwalDetail(jadwalId) {
            document.getElementById('modalDetailContent').innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;

            detailModal.show();

            fetch(`/masyarakat/rute-armada/jadwal/${jadwalId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const jadwal = data.data;
                        let lastLocationHtml = '';

                        if (jadwal.last_tracking) {
                            lastLocationHtml = `
                        <tr>
                            <th>Lokasi Terakhir:</th>
                            <td>${jadwal.last_tracking.latitude}, ${jadwal.last_tracking.longitude}<br>
                                <small>${new Date(jadwal.last_tracking.timestamp).toLocaleString('id-ID')}</small>
                            </td>
                        </tr>
                    `;
                        }

                        document.getElementById('modalDetailContent').innerHTML = `
                    <table class="table">
                        <tr>
                            <th>Armada:</th>
                            <td>${jadwal.armada.no_polisi} - ${jadwal.armada.jenis} (${jadwal.armada.kapasitas} ton)</td>
                        </tr>
                        <tr>
                            <th>Rute:</th>
                            <td>${jadwal.rute.nama_rute}</td>
                        </tr>
                        <tr>
                            <th>Jadwal:</th>
                            <td>${jadwal.jadwal.hari} - ${jadwal.jadwal.jam_aktif}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td><span class="status-badge status-${['belum', 'sedang', 'selesai'][jadwal.jadwal.status]}">${['Belum Berjalan', 'Sedang Berjalan', 'Selesai'][jadwal.jadwal.status]}</span></td>
                        </tr>
                        <tr>
                            <th>Petugas:</th>
                            <td>
                                ${jadwal.petugas.map(p => `${p.nama} (${p.posisi})`).join('<br>')}
                            </td>
                        </tr>
                        ${lastLocationHtml}
                    </table>
                `;
                    } else {
                        document.getElementById('modalDetailContent').innerHTML = `
                    <div class="alert alert-danger">
                        Gagal memuat detail jadwal.
                    </div>
                `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('modalDetailContent').innerHTML = `
                <div class="alert alert-danger">
                    Terjadi kesalahan saat memuat detail jadwal.
                </div>
            `;
                });
        }

        // Fungsi untuk menutup popup detail
        function closeJadwalPopup() {
            document.getElementById('jadwalDetailBackdrop').style.display = 'none';
            document.getElementById('jadwalDetailPopup').style.display = 'none';
        }

        // Fungsi untuk menampilkan detail armada (alias untuk showJadwalDetail)
        function showArmadaDetail(jadwalId) {
            showJadwalDetail(jadwalId);
        }
    </script>
@endpush
