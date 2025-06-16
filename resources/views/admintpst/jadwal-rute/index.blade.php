@extends('layouts.app')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <!-- Leaflet Routing Machine CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        /* Header Content Styles - Match second code */
        .content-header {
            padding: 1rem 0;
            margin-bottom: 1rem;
        }

        .content-header h4 {
            color: #495057;
            font-weight: 500;
            margin: 0;
        }

        .breadcrumb {
            background: none;
            margin: 0;
            padding: 0;
        }

        /* Card Styles - Match second code */
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
        }

        .card-success {
            border-top: 3px solid #28a745;
        }

        .card-outline {
            border: 1px solid #dee2e6;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 0.75rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h5 {
            margin: 0;
            color: #495057;
            font-weight: 500;
        }

        .card-tools .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        /* Table Styles - Match second code */
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            font-size: 0.875rem;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 500;
            text-align: center;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, .05);
        }

        /* Status Badge Styles */
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 500;
            font-size: 0.75rem;
            display: inline-block;
        }

        .status-belum {
            color: #721c24;
            background-color: #f8d7da;
        }

        .status-sedang {
            color: #856404;
            background-color: #fff3cd;
        }

        .status-selesai {
            color: #155724;
            background-color: #d4edda;
        }

        /* Button Styles - Match second code */
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        .btn-success {
            color: #fff;
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            color: #fff;
            background-color: #218838;
            border-color: #1e7e34;
        }

        .btn-outline-info {
            color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-outline-info:hover {
            color: #fff;
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .dropdown-toggle::after {
            margin-left: 0.255em;
        }

        /* Control Panel - Simplified to match second code style */
        .control-panel {
            background: white;
            border-radius: 0.5rem;
            padding: 1.25rem;
            box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
            margin-bottom: 1.5rem;
            border-top: 3px solid #28a745;
        }

        .filter-form {
            display: flex;
            gap: 15px;
            align-items: end;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            min-width: 150px;
        }

        .filter-group label {
            font-weight: 500;
            margin-bottom: 5px;
            color: #495057;
            font-size: 0.875rem;
        }

        .form-control,
        .form-select {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        /* Map Container - Keep existing styles but match card style */
        .map-container {
            background: white;
            border-radius: 0.5rem;
            padding: 1.25rem;
            box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
            margin-bottom: 1.5rem;
            border-top: 3px solid #28a745;
        }

        .map-container h4 {
            color: #495057;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        #map {
            height: 500px;
            width: 100%;
            border-radius: 0.5rem;
            border: 1px solid #dee2e6;
        }

        /* Table Container - Match second code */
        .table-container {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
            border-top: 3px solid #28a745;
            overflow: hidden;
        }

        .table-responsive {
            padding: 1.25rem;
        }

        /* Pagination */
        .pagination {
            justify-content: center;
            margin-top: 1rem;
            margin-bottom: 0;
        }

        .page-link {
            color: #007bff;
            background-color: #fff;
            border: 1px solid #dee2e6;
            padding: 0.5rem 0.75rem;
        }

        .page-link:hover {
            color: #0056b3;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .page-item.active .page-link {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        /* Modal Styles - Keep existing */
        .modal-content {
            border-radius: 0.5rem;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            background-color: #28a745;
            color: white;
            border-radius: 0.5rem 0.5rem 0 0;
            border: none;
        }

        /* Legend and other map styles - Keep existing */
        .leaflet-control.legend {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            font-size: 12px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .legend-icon {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            border-radius: 50%;
        }

        .legend-truck {
            background: #ff4757;
        }

        .legend-tps {
            background: #2ed573;
        }

        .legend-tpa {
            background: #3742fa;
        }

        .legend-route {
            width: 20px;
            height: 3px;
            background: #667eea;
            border-radius: 2px;
            margin-right: 8px;
        }

        /* Custom Popup Styles */
        .leaflet-popup-content-wrapper {
            border-radius: 0.5rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .popup-header {
            background-color: #28a745;
            color: white;
            padding: 15px;
            margin: -12px -20px 15px -20px;
            border-radius: 0.5rem 0.5rem 0 0;
            font-weight: bold;
        }

        .popup-content {
            padding: 0 5px;
        }

        .popup-footer {
            margin-top: 15px;
            text-align: center;
        }

        .btn-popup {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            color: #495057;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-popup:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
        }

        /* Tambahkan CSS ini di dalam <style> tag yang sudah ada */

        /* Filter Dropdown Styles */
        .dropdown-menu {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1rem;
        }

        .dropdown-menu .form-label {
            font-weight: 500;
            color: #495057;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .dropdown-menu .form-control,
        .dropdown-menu .form-select {
            font-size: 0.875rem;
            border-radius: 0.25rem;
        }

        .dropdown-menu .btn {
            font-size: 0.875rem;
        }

        /* Filter button active state */
        .btn-group .dropdown-toggle:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Show entries styling */
        .form-select[style*="width: auto"] {
            min-width: 70px;
        }

        /* Leaflet Routing Machine custom styles */
        .leaflet-routing-container {
            display: none;
            /* Sembunyikan panel instruksi */
        }

        .leaflet-routing-alt {
            max-height: 0;
            overflow: hidden;
        }

        .leaflet-routing-container-hide {
            display: none;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
            }

            .d-flex.align-items-center.gap-3 {
                flex-direction: column;
                align-items: stretch !important;
                gap: 0.5rem;
            }

            .dropdown-menu {
                position: static !important;
                transform: none !important;
                width: 100%;
                margin-top: 0.5rem;
                border: 1px solid #dee2e6;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
        }

        /* Remove old control panel styles since we're not using it anymore */

        /* Text utilities */
        .text-sm {
            font-size: 0.875rem !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-muted {
            color: #6c757d !important;
        }

        .text-uppercase {
            text-transform: uppercase !important;
        }

        /* Spacing utilities */
        .mb-2 {
            margin-bottom: 0.5rem !important;
        }

        .mb-3 {
            margin-bottom: 1rem !important;
        }

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        .mt-4 {
            margin-top: 1.5rem !important;
        }

        .me-1 {
            margin-right: 0.25rem !important;
        }

        .me-2 {
            margin-right: 0.5rem !important;
        }

        .ms-3 {
            margin-left: 1rem !important;
        }

        .py-4 {
            padding-top: 1.5rem !important;
            padding-bottom: 1.5rem !important;
        }
    </style>
@endpush

<!-- Bagian yang perlu diubah di section content -->

@section('content')
    <!-- Header - Match second code style -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 ">
                    <h4 class="m-0">Jadwal & Rute Operasional</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        {{-- Breadcrumb opsional --}}
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            <!-- Map Container - Pindahkan ke atas -->
            <div class="card map-container">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0">
                        <i class="fas fa-map-marked-alt me-2"></i>
                        Peta Monitoring Armada
                    </h5>
                    {{-- <div class="card-tools ms-auto">
                        <button id="hide-all-routes" class="btn btn-sm btn-secondary">
                            <i class="fas fa-eye-slash me-1"></i>Sembunyikan Semua Rute
                        </button>
                    </div> --}}
                </div>
            </div>
            <div class="card-body">
                <div id="map"></div>
            </div>
        </div>

        <!-- Filter Section - Pindahkan ke bawah map dengan tampilan baru -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Filter Button -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                        <div class="dropdown-menu p-3" style="min-width: 300px;">
                            <form method="GET" action="{{ route('jadwal-rute.index') }}" id="filterForm">
                                <div class="mb-3">
                                    <label for="search" class="form-label">Cari</label>
                                    <input type="text" class="form-control form-control-sm" id="search" name="search"
                                        value="{{ $currentSearch }}" placeholder="No. Polisi, Rute...">
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select form-select-sm" id="status" name="status">
                                        <option value="">Semua Status</option>
                                        <option value="0" {{ $currentStatus == '0' ? 'selected' : '' }}>Belum
                                            Berjalan</option>
                                        <option value="1" {{ $currentStatus == '1' ? 'selected' : '' }}>Berjalan
                                        </option>
                                        <option value="2" {{ $currentStatus == '2' ? 'selected' : '' }}>Selesai
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="date" class="form-label">Tanggal</label>
                                    <input type="date" class="form-control form-control-sm" id="date" name="date"
                                        value="{{ $currentDate }}">
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                        <i class="fas fa-search me-1"></i>Terapkan
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="resetFilter()">
                                        <i class="fas fa-times me-1"></i>Reset
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Show entries dan Export -->
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center">
                            <label for="per_page" class="me-2 text-muted">Show</label>
                            <select class="form-select form-select-sm" id="per_page" name="per_page"
                                onchange="changePerPage()" style="width: auto;">
                                <option value="5" {{ $currentPerPage == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $currentPerPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $currentPerPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $currentPerPage == 50 ? 'selected' : '' }}>50</option>
                            </select>
                            <span class="ms-2 text-muted">entries</span>
                        </div>

                        <button type="button" class="btn btn-success btn-sm" onclick="exportData()">
                            <i class="fas fa-download me-2"></i>Export
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="card card-success card-outline table-container">
            <div class="card-header">
                <h5 class="m-0">Data Jadwal Operasional</h5>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-success" onclick="window.location.reload()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
            <!-- Sisa kode table tetap sama -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-sm">
                        <thead class="text-center">
                            <tr>
                                <th>ID Jadwal</th>
                                <th>Tanggal</th>
                                <th>Hari</th>
                                <th>Armada</th>
                                <th>Rute</th>
                                <th>Jam Aktif</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tableData as $data)
                                <tr>
                                    <td class="text-center">{{ $data['id'] }}</td>
                                    <td class="text-center">{{ $data['tanggal'] }}</td>
                                    <td class="text-center">{{ $data['hari'] }}</td>
                                    <td class="text-center">{{ $data['armada'] }}</td>
                                    <td class="text-center">{{ $data['rute'] }}</td>
                                    <td class="text-center">{{ $data['jam_aktif'] }}</td>
                                    <td class="text-center">
                                        <span
                                            class="status-badge {{ $data['status_class'] == 'badge-danger' ? 'status-belum' : ($data['status_class'] == 'badge-warning' ? 'status-sedang' : 'status-selesai') }}">
                                            {{ $data['status_text'] }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-info dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#"
                                                    onclick="showArmadaDetail({{ $data['id'] }})">
                                                    <i class="fas fa-eye me-1"></i>Detail
                                                </a>
                                                {{-- <a class="dropdown-item" href="#"
                                                        onclick="showRouteForArmada({{ $data['id'] }})">
                                                        <i class="fas fa-route me-1"></i>Tampilkan Rute
                                                    </a> --}}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Tidak ada data jadwal operasional</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($jadwalOperasional->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $jadwalOperasional->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>

    <!-- Modal Detail Armada tetap sama -->
    <div class="modal fade" id="armadaDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-truck me-2"></i>
                        Detail Armada
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="armadaDetailContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <!-- Leaflet Routing Machine JS -->
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

    <script>
        // Data dari controller
        const mapData = @json($mapData);
        const allTpsForMap = @json($allTpsForMap);

        // Inisialisasi map
        let map = L.map('map').setView([-7.056325, 110.454250], 15); // Default ke Semarang

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Variabel untuk menyimpan markers dan routes
        let armadaMarkers = [];
        let tpsMarkers = [];
        let routeLines = [];
        let routeControl = null;
        let isRouteVisible = true;
        let activeJadwalId = null;

        // Tambahkan legenda ke peta
        const legend = L.control({
            position: 'bottomright'
        });

        legend.onAdd = function(map) {
            const div = L.DomUtil.create('div', 'leaflet-control legend');
            div.innerHTML = `
        <h6><strong>Legenda</strong></h6>
        <div class="legend-item">
            <div class="legend-icon legend-truck"></div>
            <span>Armada</span>
        </div>
        <div class="legend-item">
            <div class="legend-icon legend-tps"></div>
            <span>TPS</span>
        </div>
        <div class="legend-item">
            <div class="legend-icon legend-tpa"></div>
            <span>TPA</span>
        </div>
        <div class="legend-item">
            <div class="legend-route"></div>
            <span>Rute</span>
        </div>
    `;
            return div;
        };

        legend.addTo(map);

        // Function untuk inisialisasi peta
        function initializeMap() {
            // Clear existing markers and routes
            clearMapElements();

            // Add TPS markers
            addTpsMarkers();

            // Add armada markers
            addArmadaMarkers();

            // Fit bounds if there's data
            if (mapData.length > 0 || allTpsForMap.length > 0) {
                fitMapBounds();
            }
        }

        // Function untuk clear semua element di map
        function clearMapElements() {
            armadaMarkers.forEach(marker => map.removeLayer(marker));
            tpsMarkers.forEach(marker => map.removeLayer(marker));
            routeLines.forEach(line => map.removeLayer(line));

            if (routeControl) {
                map.removeControl(routeControl);
                routeControl = null;
            }

            armadaMarkers = [];
            tpsMarkers = [];
            routeLines = [];
        }

        // Function untuk add TPS markers
        function addTpsMarkers() {
            allTpsForMap.forEach(tps => {
                const icon = L.divIcon({
                    html: `<div style="background: ${getTpsColor(tps.tipe)}; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.3);"></div>`,
                    className: 'tps-marker',
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                });

                const marker = L.marker([tps.latitude, tps.longitude], {
                        icon
                    })
                    .bindPopup(`
                    <div class="popup-header">${tps.nama_lokasi}</div>
                    <div class="popup-content">
                        <p><strong>Jenis:</strong> ${tps.tipe}</p>
                    </div>
                `)
                    .addTo(map);

                tpsMarkers.push(marker);
            });
        }

        // Function untuk add armada markers
        function addArmadaMarkers() {
            mapData.forEach(jadwal => {
                if (jadwal.last_tracking) {
                    const icon = L.divIcon({
                        html: `<div style="background: #ff4757; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold;"><i class="fas fa-truck" style="font-size: 10px;"></i></div>`,
                        className: 'truck-marker',
                        iconSize: [24, 24],
                        iconAnchor: [12, 12]
                    });

                    const marker = L.marker([jadwal.last_tracking.latitude, jadwal.last_tracking.longitude], {
                            icon
                        })
                        .bindPopup(`
                        <div class="popup-header">
                            <i class="fas fa-truck me-2"></i>
                            ${jadwal.armada.no_polisi}
                        </div>
                        <div class="popup-content">
                            <p><strong>Rute:</strong> ${jadwal.rute.nama}</p>
                            <p><strong>Status:</strong> <span class="badge bg-${getStatusColor(jadwal.status)}">${jadwal.status_text}</span></p>
                            <p><strong>Jam Aktif:</strong> ${jadwal.jam_aktif}</p>
                            <p><strong>Update Terakhir:</strong> ${new Date(jadwal.last_tracking.timestamp).toLocaleString('id-ID')}</p>
                        </div>
                        <div class="popup-footer">
                            <button class="btn-popup" onclick="showRouteForArmada(${jadwal.id})">
                                <i class="fas fa-route me-1"></i>Tampilkan Rute
                            </button>
                            <button class="btn-popup" onclick="showArmadaDetail(${jadwal.id})">
                                <i class="fas fa-info-circle me-1"></i>Detail
                            </button>
                        </div>
                    `)
                        .addTo(map);

                    // Tambahkan ID jadwal ke marker untuk referensi
                    marker.jadwalId = jadwal.id;
                    armadaMarkers.push(marker);
                }
            });
        }

        // Function untuk show rute specific armada dengan routing
        function showRouteForArmada(jadwalId) {
            // Clear existing routes
            clearRoutes();

            // Set jadwal aktif
            activeJadwalId = jadwalId;

            const jadwal = mapData.find(j => j.id === jadwalId);
            if (!jadwal || !jadwal.tps_data || jadwal.tps_data.length === 0) {
                console.warn('Tidak ada data TPS untuk jadwal ini');
                return;
            }

            // Buat waypoints dari data TPS yang sudah diurutkan
            const waypoints = [];
            let armadaPosition = null;

            // Tambahkan posisi armada sebagai titik awal jika tersedia
            if (jadwal.last_tracking) {
                armadaPosition = L.latLng(jadwal.last_tracking.latitude, jadwal.last_tracking.longitude);
                waypoints.push(armadaPosition);
            }

            // Tambahkan semua TPS ke waypoints - data sudah terurut dari controller
            jadwal.tps_data.forEach(tps => {
                waypoints.push(L.latLng(tps.latitude, tps.longitude));
            });

            if (waypoints.length < 2) {
                alert('Minimal dibutuhkan 2 titik untuk membuat rute');
                return;
            }

            // Tambahkan marker urutan TPS (terlepas dari tipe rute yang akan digunakan)
            jadwal.tps_data.forEach((tps, index) => {
                const routeMarker = L.divIcon({
                    html: `<div style="background: ${jadwal.rute.color || '#3388ff'}; width: 25px; height: 25px; border-radius: 50%; border: 2px solid white; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 12px;">${tps.urutan || (index + 1)}</div>`,
                    className: 'route-marker',
                    iconSize: [25, 25],
                    iconAnchor: [12, 12]
                });

                const marker = L.marker([tps.latitude, tps.longitude], {
                        icon: routeMarker
                    })
                    .bindPopup(`
            <div class="popup-header">TPS ${tps.urutan || (index + 1)}: ${tps.nama_lokasi}</div>
            <div class="popup-content">
                <p><strong>Jenis:</strong> ${tps.tipe}</p>
                <p><strong>Urutan:</strong> ${tps.urutan || (index + 1)}</p>
                <p><strong>Rute:</strong> ${jadwal.rute.nama}</p>
            </div>
        `).addTo(map);

                routeLines.push(marker);
            });

            // Variable untuk mendeteksi timeout
            let routeTimedOut = false;
            const timeoutDuration = 15000; // 15 detik
            let routeTimeout = setTimeout(function() {
                routeTimedOut = true;
                console.warn('OSRM request timed out, menggunakan rute sederhana sebagai fallback');

                // Hapus kontrol routing yang mungkin sedang loading
                if (routeControl) {
                    map.removeControl(routeControl);
                    routeControl = null;
                }

                // Gambar rute sederhana sebagai fallback
                drawSimpleRouteForArmada(jadwal);
            }, timeoutDuration);

            // Coba gunakan OSRM terlebih dahulu
            routeControl = L.Routing.control({
                waypoints: waypoints,
                routeWhileDragging: false,
                showAlternatives: false,
                fitSelectedRoutes: true,
                show: false, // Jangan tampilkan panel instruksi
                lineOptions: {
                    styles: [{
                        color: jadwal.rute.color || '#3388ff',
                        opacity: 0.7,
                        weight: 6
                    }, {
                        color: 'white',
                        opacity: 0.5,
                        weight: 2
                    }]
                },
                createMarker: function() {
                    return null; // Jangan buat marker di sepanjang rute
                },
                router: L.Routing.osrmv1({
                    serviceUrl: 'https://router.project-osrm.org/route/v1',
                    profile: 'driving',
                    useHints: false,
                    geometryOnly: false,
                    suppressDemoServerWarning: true,
                    roundTrip: false,
                    alternatives: false,
                    steps: true,
                    overview: "full",
                    geometries: "polyline",
                    timeout: 12000 // 12 detik timeout (lebih rendah dari timeoutDuration)
                })
            }).addTo(map);

            // Jika rute ditemukan, batalkan timeout dan gunakan rute OSRM
            routeControl.on('routesfound', function(e) {
                clearTimeout(routeTimeout); // Batalkan timeout fallback

                if (!routeTimedOut) { // Hanya proses jika belum timeout
                    const routes = e.routes;
                    console.log('Rute dari armada ke TPS ditemukan:', routes);

                    // Fit bounds to route with padding
                    if (routes.length > 0) {
                        map.fitBounds(routes[0].bounds, {
                            padding: [50, 50]
                        });
                    }

                    isRouteVisible = true;

                    // Tampilkan notifikasi sukses (opsional)
                    showNotification('success', 'Rute optimal berhasil dimuat', 3000);
                }
            });

            // Jika terjadi error routing, gunakan rute sederhana
            routeControl.on('routingerror', function(e) {
                console.warn('Routing error:', e.error);

                // Batalkan timeout fallback karena sudah ada error yang tertangkap
                clearTimeout(routeTimeout);

                // Hapus kontrol routing yang error
                if (routeControl && !routeTimedOut) {
                    map.removeControl(routeControl);
                    routeControl = null;

                    // Gambar rute sederhana sebagai fallback
                    drawSimpleRouteForArmada(jadwal);
                }
            });
        }

        // Fungsi untuk menggambar rute sederhana sebagai fallback
        function drawSimpleRouteForArmada(jadwal) {
            // Hapus rute sederhana yang sudah ada jika ada
            if (window.simplePath) {
                map.removeLayer(window.simplePath);
            }

            const coordinates = [];

            // Mulai dari posisi armada jika tersedia
            if (jadwal.last_tracking) {
                coordinates.push([
                    parseFloat(jadwal.last_tracking.latitude),
                    parseFloat(jadwal.last_tracking.longitude)
                ]);
            }

            // Tambahkan semua TPS ke koordinat
            jadwal.tps_data.forEach(tps => {
                coordinates.push([
                    parseFloat(tps.latitude),
                    parseFloat(tps.longitude)
                ]);
            });

            if (coordinates.length < 2) {
                console.warn('Tidak cukup koordinat untuk membuat rute sederhana');
                return;
            }

            // Buat polyline sederhana dengan warna yang sama seperti rute asli
            window.simplePath = L.polyline(coordinates, {
                color: jadwal.rute.color || '#3388ff',
                weight: 4,
                opacity: 0.7,
                dashArray: '10, 10' // Garis putus-putus untuk menunjukkan ini bukan rute optimal
            }).addTo(map);

            // Tambahkan popup ke garis untuk memberi tahu pengguna
            window.simplePath.bindPopup('Rute sederhana (bukan rute jalan sebenarnya)');

            // Fit bounds pada semua titik
            const bounds = L.latLngBounds(coordinates.map(coord => L.latLng(coord[0], coord[1])));
            map.fitBounds(bounds, {
                padding: [50, 50]
            });

            isRouteVisible = true;
            routeLines.push(window.simplePath);

            // Tampilkan pesan notifikasi
            showNotification('info', 'Menggunakan rute sederhana karena rute jalan tidak tersedia', 5000);
        }

        // Fungsi helper untuk menampilkan notifikasi
        function showNotification(type, message, duration = 5000) {
            // Cek apakah container notifikasi sudah ada
            let notifContainer = document.getElementById('map-notifications');

            if (!notifContainer) {
                // Buat container notifikasi jika belum ada
                notifContainer = document.createElement('div');
                notifContainer.id = 'map-notifications';
                notifContainer.style.cssText =
                    'position: absolute; top: 10px; right: 10px; z-index: 1000; max-width: 300px;';
                document.querySelector('.leaflet-container').appendChild(notifContainer);
            }

            // Buat elemen notifikasi
            const notif = document.createElement('div');
            notif.className = `alert alert-${type} alert-dismissible fade show`;
            notif.style.cssText = 'margin-bottom: 10px; padding: 10px 15px; font-size: 14px; opacity: 0.9;';
            notif.innerHTML = `
        ${message}
        <button type="button" class="btn-close" style="font-size: 10px; padding: 8px;" onclick="this.parentElement.remove()"></button>
    `;

            notifContainer.appendChild(notif);

            // Hapus notifikasi setelah durasi tertentu
            setTimeout(() => {
                notif.remove();
            }, duration);
        }
        // Function untuk toggle visibilitas rute
        function toggleRouteVisibility() {
            if (!routeControl) {
                return;
            }

            if (isRouteVisible) {
                // Sembunyikan rute
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
                // Tampilkan rute
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
        }

        // Function untuk membersihkan semua rute
        function clearRoutes() {
            routeLines.forEach(line => map.removeLayer(line));
            routeLines = [];

            if (routeControl) {
                map.removeControl(routeControl);
                routeControl = null;
            }
        }

        // Function untuk fit map bounds
        function fitMapBounds() {
            let bounds = [];

            // Add armada positions
            mapData.forEach(jadwal => {
                if (jadwal.last_tracking) {
                    bounds.push([jadwal.last_tracking.latitude, jadwal.last_tracking.longitude]);
                }
            });

            // Add TPS positions
            allTpsForMap.forEach(tps => {
                bounds.push([tps.latitude, tps.longitude]);
            });

            if (bounds.length > 0) {
                map.fitBounds(bounds, {
                    padding: [20, 20]
                });
            }
        }

        function getTpsColor(jenis) {
            switch (jenis?.toLowerCase()) {
                case 'tpa':
                    return '#3742fa';
                case 'tps':
                    return '#2ed573';
                default:
                    return '#2ed573';
            }
        }

        function getStatusColor(status) {
            switch (status) {
                case 0:
                    return 'danger';
                case 1:
                    return 'warning';
                case 2:
                    return 'success';
                default:
                    return 'secondary';
            }
        }

        // Function untuk show detail armada
        function showArmadaDetail(jadwalId) {
            const modal = new bootstrap.Modal(document.getElementById('armadaDetailModal'));
            const content = document.getElementById('armadaDetailContent');

            // Show loading
            content.innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat detail armada...</p>
                </div>
            `;

            modal.show();

            // Fetch detail - sesuaikan dengan route dari controller
            fetch(`jadwal-rute/api/armada-detail/${jadwalId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const detail = data.data;
                        content.innerHTML = `
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-truck me-2"></i>Informasi Armada
                                    </h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>ID Jadwal:</strong></td>
                                            <td>${detail.id}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>No. Polisi:</strong></td>
                                            <td>${detail.armada.no_polisi}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jenis:</strong></td>
                                            <td>${detail.armada.jenis}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kapasitas:</strong></td>
                                            <td>${detail.armada.kapasitas} ton</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-success mb-3">
                                        <i class="fas fa-route me-2"></i>Informasi Jadwal
                                    </h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Rute:</strong></td>
                                            <td>${detail.rute.nama}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Hari:</strong></td>
                                            <td>${detail.jadwal.hari}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jam Aktif:</strong></td>
                                            <td>${detail.jadwal.jam_aktif}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="status-badge ${getStatusBadgeClass(detail.status)}">
                                                    ${detail.status_text}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            ${detail.petugas && detail.petugas.length > 0 ? `
                                                            <div class="row mt-4">
                                                                <div class="col-12">
                                                                    <h6 class="text-info mb-3">
                                                                        <i class="fas fa-users me-2"></i>Tim Petugas
                                                                    </h6>
                                                                    <div class="row">
                                                                        ${detail.petugas.map(petugas => `
                                                <div class="col-md-6 mb-2">
                                                    <div class="card border-0 bg-light">
                                                        <div class="card-body py-2">
                                                            <small class="text-muted">${petugas.tugas}</small>
                                                            <div class="fw-bold">${petugas.nama}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            `).join('')}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        ` : ''}

                            ${detail.rute.tps_points && detail.rute.tps_points.length > 0 ? `
                                                            <div class="row mt-4">
                                                                <div class="col-12">
                                                                    <h6 class="text-primary mb-3">
                                                                        <i class="fas fa-map-marker-alt me-2"></i>Daftar TPS
                                                                    </h6>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-sm table-bordered">
                                                                            <thead class="table-light">
                                                                                <tr>
                                                                                    <th>Urutan</th>
                                                                                    <th>Nama TPS</th>
                                                                                    <th>Tipe</th>
                                                                                    <th>Koordinat</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                ${detail.rute.tps_points.map(tps => `
                                                        <tr>
                                                            <td class="text-center">${tps.urutan || '-'}</td>
                                                            <td>${tps.nama_lokasi}</td>
                                                            <td>${tps.tipe || 'TPS'}</td>
                                                            <td>${tps.latitude.toFixed(6)}, ${tps.longitude.toFixed(6)}</td>
                                                        </tr>
                                                    `).join('')}
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        ` : ''}

                            ${detail.last_tracking ? `
                                                            <div class="row mt-4">
                                                                <div class="col-12">
                                                                    <h6 class="text-warning mb-3">
                                                                        <i class="fas fa-map-marker-alt me-2"></i>Tracking Terakhir
                                                                    </h6>
                                                                    <div class="card border-0 bg-light">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-4">
                                                                                    <small class="text-muted">Latitude</small>
                                                                                    <div class="fw-bold">${detail.last_tracking.latitude}</div>
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <small class="text-muted">Longitude</small>
                                                                                    <div class="fw-bold">${detail.last_tracking.longitude}</div>
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <small class="text-muted">Waktu Update</small>
                                                                                    <div class="fw-bold">${detail.last_tracking.timestamp}</div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        ` : `
                                                            <div class="row mt-4">
                                                                <div class="col-12">
                                                                    <div class="alert alert-info text-center">
                                                                        <i class="fas fa-info-circle me-2"></i>
                                                                        Belum ada data tracking untuk armada ini
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        `}
                        `;
                    } else {
                        content.innerHTML = `
                            <div class="alert alert-danger text-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Gagal memuat detail armada: ${data.message}
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    console.log('Error Response:', error.response); // jika pakai Axios, ini penting
                    console.log('Full Error Object:', JSON.stringify(error)); // untuk debugging tambahan

                    content.innerHTML = `
                        <div class="alert alert-danger text-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Terjadi kesalahan saat memuat detail armada
                        </div>
                    `;
                });

        }

        // Function helper untuk status badge class
        function getStatusBadgeClass(status) {
            switch (status) {
                case 0:
                    return 'status-belum';
                case 1:
                    return 'status-sedang';
                case 2:
                    return 'status-selesai';
                default:
                    return 'status-belum';
            }
        }

        // Function untuk export data
        function exportData() {
            const params = new URLSearchParams(window.location.search);
            const exportUrl = `jadwal-rute/export?${params.toString()}`;

            // Show loading
            const exportBtn = document.querySelector('.btn:contains("Export")') || document.querySelector(
                '[onclick="exportData()"]');
            if (exportBtn) {
                const originalText = exportBtn.innerHTML;
                exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Exporting...';
                exportBtn.disabled = true;

                fetch(exportUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Convert to CSV and download
                            const csv = convertToCSV(data.data);
                            downloadCSV(csv, data.filename + '.csv');
                        } else {
                            alert('Gagal export data: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Export error:', error);
                        alert('Terjadi kesalahan saat export data');
                    })
                    .finally(() => {
                        if (exportBtn) {
                            exportBtn.innerHTML = originalText;
                            exportBtn.disabled = false;
                        }
                    });
            }
        }

        // Function untuk convert data ke CSV
        function convertToCSV(data) {
            if (data.length === 0) return '';

            const headers = Object.keys(data[0]);
            const csvRows = [];

            // Add headers
            csvRows.push(headers.join(','));

            // Add data rows
            for (const row of data) {
                const values = headers.map(header => {
                    const escaped = ('' + row[header]).replace(/"/g, '\\"');
                    return `"${escaped}"`;
                });
                csvRows.push(values.join(','));
            }

            return csvRows.join('\n');
        }

        // Function untuk download CSV
        function downloadCSV(csv, filename) {
            const blob = new Blob([csv], {
                type: 'text/csv'
            });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.setAttribute('hidden', '');
            a.setAttribute('href', url);
            a.setAttribute('download', filename);
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }

        // Function untuk update tracking real-time
        function startTrackingUpdates() {
            setInterval(() => {
                updateArmadaPositions();
            }, 30000); // Update setiap 30 detik
        }

        // Function untuk update posisi armada
        function updateArmadaPositions() {
            mapData.forEach(jadwal => {
                if (jadwal.status === 1) { // Hanya update yang sedang berjalan
                    fetch(`jadwal-rute/api/tracking/${jadwal.id}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update marker position
                                const armadaMarker = armadaMarkers.find(m => m.jadwalId === jadwal.id);
                                if (armadaMarker) {
                                    armadaMarker.setLatLng([data.data.latitude, data.data.longitude]);

                                    // Update popup content
                                    const popupContent = `
                                        <div class="popup-header">
                                            <i class="fas fa-truck me-2"></i>
                                            ${jadwal.armada.no_polisi}
                                        </div>
                                        <div class="popup-content">
                                            <p><strong>Rute:</strong> ${jadwal.rute.nama}</p>
                                            <p><strong>Status:</strong> <span class="badge bg-${getStatusColor(jadwal.status)}">${jadwal.status_text}</span></p>
                                            <p><strong>Jam Aktif:</strong> ${jadwal.jam_aktif}</p>
                                            <p><strong>Update Terakhir:</strong> ${new Date(data.data.timestamp).toLocaleString('id-ID')}</p>
                                        </div>
                                        <div class="popup-footer">
                                            <button class="btn-popup" onclick="showRouteForArmada(${jadwal.id})">
                                                <i class="fas fa-route me-1"></i>Tampilkan Rute
                                            </button>
                                            <button class="btn-popup" onclick="showArmadaDetail(${jadwal.id})">
                                                <i class="fas fa-info-circle me-1"></i>Detail
                                            </button>
                                        </div>
                                    `;
                                    armadaMarker.setPopupContent(popupContent);

                                    // Update rute jika sedang aktif
                                    if (routeControl && activeJadwalId === jadwal.id && isRouteVisible) {
                                        const waypoints = routeControl.getWaypoints();
                                        if (waypoints.length > 0) {
                                            waypoints[0].latLng = L.latLng(data.data.latitude, data.data
                                                .longitude);
                                            routeControl.setWaypoints(waypoints);
                                        }
                                    }
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error updating tracking for jadwal', jadwal.id, ':', error);
                        });
                }
            });
        }

        // Function untuk handle resize map
        function handleMapResize() {
            window.addEventListener('resize', function() {
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);
            });
        }

        // Function untuk clear route ketika klik di tempat lain
        function setupMapClickHandler() {
            map.on('click', function(e) {
                // Clear routes when clicking on empty space
                if (e.originalEvent.target === map.getContainer()) {
                    clearRoutes();
                }
            });
        }

        // Function untuk handle keyboard shortcuts
        function setupKeyboardShortcuts() {
            document.addEventListener('keydown', function(e) {
                // Esc key untuk clear routes
                if (e.key === 'Escape') {
                    clearRoutes();
                }

                // F5 untuk refresh tracking
                if (e.key === 'F5' && e.ctrlKey) {
                    e.preventDefault();
                    updateArmadaPositions();
                }
            });
        }

        // Function untuk handle perubahan per page
        function changePerPage() {
            const perPage = document.getElementById('per_page').value;
            const url = new URL(window.location);
            url.searchParams.set('per_page', perPage);
            url.searchParams.delete('page'); // Reset halaman ke 1
            window.location.href = url.toString();
        }

        // Function untuk reset filter
        function resetFilter() {
            const url = new URL(window.location);
            // Hapus semua parameter filter
            url.searchParams.delete('search');
            url.searchParams.delete('status');
            url.searchParams.delete('date');
            url.searchParams.delete('page');
            // Tutup dropdown
            const dropdown = bootstrap.Dropdown.getInstance(document.querySelector('[data-bs-toggle="dropdown"]'));
            if (dropdown) {
                dropdown.hide();
            }
            window.location.href = url.toString();
        }

        // // Event listener untuk tombol hide all routes
        // document.getElementById('hide-all-routes').addEventListener('click', clearRoutes);

        // Event listener untuk tombol toggle route
        // document.getElementById('toggle-route').addEventListener('click', toggleRouteVisibility);

        // Initialize everything when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeMap();
            handleMapResize();
            setupMapClickHandler();
            setupKeyboardShortcuts();

            // Start real-time tracking updates (optional)
            // startTrackingUpdates();

            console.log('Peta jadwal operasional berhasil dimuat');
            console.log('Data armada:', mapData.length);
            console.log('Data TPS:', allTpsForMap.length);
            // Handle dropdown events
            const filterDropdown = document.querySelector('.dropdown-menu');
            if (filterDropdown) {
                // Prevent dropdown from closing when clicking inside
                filterDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });

        // Global functions for window access
        window.exportData = exportData;
        window.showArmadaDetail = showArmadaDetail;
        window.showRouteForArmada = showRouteForArmada;
        window.toggleRouteVisibility = toggleRouteVisibility;
        window.clearRoutes = clearRoutes;
        window.updateArmadaPositions = updateArmadaPositions;
        // Update existing window functions
        window.changePerPage = changePerPage;
        window.resetFilter = resetFilter;
    </script>
@endpush
