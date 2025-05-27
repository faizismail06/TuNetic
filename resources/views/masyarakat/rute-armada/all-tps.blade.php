@extends('components.navbar')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
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

        .info-box {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .tps-list-card {
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .tps-list-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .legend-box {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .legend-icon {
            width: 24px;
            height: 24px;
            display: inline-block;
            margin-right: 10px;
            border-radius: 50%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
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

        .type-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-tps {
            background-color: #e3f2fd;
            color: #1565c0;
        }

        .badge-tpst {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .badge-tpa {
            background-color: #fff3e0;
            color: #ef6c00;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1 class="mb-0 fs-4 fw-bold d-flex align-items-center">
                                <i class="fas fa-map-marked-alt me-2"></i>
                                Lokasi Fasilitas Pengelolaan Sampah
                            </h1>
                            <a href="{{ route('masyarakat.rute-armada.index') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke Rute
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Informasi Umum -->
                        <div class="info-box">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="fw-bold mb-2">Informasi:</h6>
                                    <ul class="mb-0">
                                        <li>Total <strong>{{ count($allTps) }}</strong> lokasi fasilitas pengelolaan sampah
                                        </li>
                                        <li>Terdiri dari TPS (Tempat Pembuangan Sementara), TPST (Tempat Pengolahan Sampah
                                            Terpadu), dan TPA (Tempat Pemrosesan Akhir)</li>
                                        <li>Klik pada marker untuk melihat detail lokasi</li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <div class="legend-box">
                                        <h6 class="fw-bold mb-2">Jenis Fasilitas:</h6>
                                        <div class="legend-item">
                                            <div class="legend-icon" style="background-color: #2196f3;">
                                                <i class="fas fa-trash"></i>
                                            </div>
                                            <span>TPS - Tempat Pembuangan Sementara</span>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-icon" style="background-color: #4caf50;">
                                                <i class="fas fa-recycle"></i>
                                            </div>
                                            <span>TPST - Tempat Pengolahan Sampah Terpadu</span>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-icon" style="background-color: #ff9800;">
                                                <i class="fas fa-industry"></i>
                                            </div>
                                            <span>TPA - Tempat Pemrosesan Akhir</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Peta -->
                        <div class="map-card mb-4">
                            <div id="map"></div>
                        </div>

                        <!-- Daftar TPS -->
                        <div>
                            <h6 class="mb-3 fw-bold">Daftar Fasilitas Pengelolaan Sampah:</h6>
                            <div class="row">
                                @foreach ($allTps as $tps)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card tps-list-card h-100" data-tps-id="{{ $tps['id'] }}"
                                            data-lat="{{ $tps['latitude'] }}" data-lng="{{ $tps['longitude'] }}">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 fw-bold">{{ $tps['nama'] }}</h6>
                                                <span
                                                    class="type-badge badge-{{ strtolower($tps['jenis']) }}">{{ $tps['jenis'] }}</span>
                                            </div>
                                            <div class="card-body">
                                                <p class="mb-2">
                                                    <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                                    <small
                                                        class="text-muted">{{ $tps['alamat'] ?: 'Alamat tidak tersedia' }}</small>
                                                </p>
                                                <p class="mb-2">
                                                    <i class="fas fa-map text-muted me-1"></i>
                                                    <small class="text-muted">{{ $tps['latitude'] }},
                                                        {{ $tps['longitude'] }}</small>
                                                </p>
                                                <button class="btn btn-sm btn-primary show-on-map w-100">
                                                    <i class="fas fa-eye me-1"></i> Lihat di Peta
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data untuk JavaScript -->
    <script>
        const allTps = @json($allTps);
    </script>
@endsection

@push('js')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        // Variabel global
        let map;
        let markersLayer = L.layerGroup();
        let selectedMarker = null;

        // Mapping jenis ke icon dan warna
        const typeConfig = {
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

        // Inisialisasi peta
        document.addEventListener('DOMContentLoaded', function() {
            initializeMap();
            setupEventListeners();
            showAllTps();
        });

        // Fungsi inisialisasi peta
        function initializeMap() {
            // Inisialisasi peta dengan lokasi default Jawa Tengah
            map = L.map('map').setView([-7.056325, 110.454250], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Tambahkan layer group
            markersLayer.addTo(map);

            // Tambahkan legenda
            addMapLegend();
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
                        <div class="legend-icon" style="background-color: #2196f3;">
                            <i class="fas fa-trash"></i>
                        </div>
                        <span>TPS</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-icon" style="background-color: #4caf50;">
                            <i class="fas fa-recycle"></i>
                        </div>
                        <span>TPST</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-icon" style="background-color: #ff9800;">
                            <i class="fas fa-industry"></i>
                        </div>
                        <span>TPA</span>
                    </div>
                `;
                return div;
            };

            legend.addTo(map);
        }

        // Tampilkan semua TPS
        function showAllTps() {
            markersLayer.clearLayers();

            allTps.forEach(tps => {
                const config = typeConfig[tps.jenis.toUpperCase()] || typeConfig['TPS'];

                const marker = L.marker([tps.latitude, tps.longitude], {
                    icon: L.divIcon({
                        className: 'tps-marker',
                        html: `<div style="background-color: ${config.color}; width: 32px; height: 32px; border-radius: 50%; border: 3px solid white; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
                                <i class="fas ${config.icon}"></i>
                            </div>`,
                        iconSize: [32, 32],
                        iconAnchor: [16, 32]
                    })
                });

                marker.bindPopup(`
                    <div class="p-3" style="max-width: 250px;">
                        <h6 class="fw-bold mb-2">${tps.nama}</h6>
                        <p class="mb-1">
                            <span class="badge" style="background-color: ${config.color}; color: white;">
                                ${tps.jenis}
                            </span>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-map-marker-alt text-muted me-1"></i>
                            <small>${tps.alamat || 'Alamat tidak tersedia'}</small>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-map text-muted me-1"></i>
                            <small>${tps.latitude}, ${tps.longitude}</small>
                        </p>
                    </div>
                `);

                markersLayer.addLayer(marker);
            });

            // Fit bounds jika ada data
            if (markersLayer.getLayers().length > 0) {
                map.fitBounds(markersLayer.getBounds().pad(0.1));
            }
        }

        // Setup event listeners
        function setupEventListeners() {
            // Event listener untuk tombol "Lihat di Peta"
            document.querySelectorAll('.show-on-map').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const card = this.closest('.tps-list-card');
                    const lat = parseFloat(card.dataset.lat);
                    const lng = parseFloat(card.dataset.lng);

                    // Zoom ke lokasi TPS
                    map.setView([lat, lng], 17);

                    // Buka popup TPS yang dipilih
                    markersLayer.eachLayer(function(layer) {
                        if (layer.getLatLng().lat === lat && layer.getLatLng().lng === lng) {
                            layer.openPopup();
                        }
                    });

                    // Scroll to map
                    document.getElementById('map').scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });

                    // Highlight card
                    document.querySelectorAll('.tps-list-card').forEach(c => c.style.boxShadow = '');
                    card.style.boxShadow = '0 0 15px rgba(0,123,255,0.5)';
                });
            });

            // Event listener untuk click pada card TPS
            document.querySelectorAll('.tps-list-card').forEach(card => {
                card.addEventListener('click', function() {
                    this.querySelector('.show-on-map').click();
                });
            });
        }

        // Auto-remove highlight setelah 3 detik
        setInterval(function() {
            document.querySelectorAll('.tps-list-card').forEach(card => {
                if (card.style.boxShadow.includes('rgb(0, 123, 255)')) {
                    card.style.boxShadow = '';
                }
            });
        }, 3000);
    </script>
@endpush
