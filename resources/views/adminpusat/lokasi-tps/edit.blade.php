@extends('layouts.app')

@push('css')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
        integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
    <!-- Leaflet Geocoder CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css"
        rel="stylesheet">
    <style>
        #map {
            height: 400px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .coordinates-display {
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        .search-container {
            margin-bottom: 10px;
        }

        .search-container .input-group {
            margin-bottom: 8px;
        }

        /* Custom Select2 styling */
        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(2.25rem + 2px) !important;
        }

        .select2-container--bootstrap4 .select2-selection__rendered {
            padding-left: 12px;
            padding-right: 12px;
        }

        .select2-container--bootstrap4 .select2-selection__arrow {
            height: calc(2.25rem + 2px) !important;
        }

        .select2-dropdown {
            z-index: 9999 !important;
        }

        /* Loading state styling */
        .select2-results__option--loading-results {
            background-color: #f8f9fa;
            color: #6c757d;
            font-style: italic;
        }

        .select2-results__option--no-results {
            background-color: #f8f9fa;
            color: #6c757d;
        }

        /* Current location badge */
        .current-location-badge {
            background-color: #28a745;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            margin-left: 5px;
        }

        .location-info {
            background-color: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .location-info h6 {
            color: #0056b3;
            margin-bottom: 5px;
        }

        .location-info small {
            color: #666;
        }
    </style>
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Lokasi TPS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('lokasi-tps.index') }}">Lokasi TPS</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Form Edit Lokasi TPS</h3>
                        </div>
                        <form action="{{ route('lokasi-tps.update', $lokasiTps->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama_lokasi">Nama Lokasi TPS <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('nama_lokasi') is-invalid @enderror"
                                                id="nama_lokasi" name="nama_lokasi"
                                                value="{{ old('nama_lokasi', $lokasiTps->nama_lokasi) }}" required>
                                            @error('nama_lokasi')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="tipe">Kategori TPS <span class="text-danger">*</span></label>
                                            <select
                                                class="form-control select2-searchable @error('tipe') is-invalid @enderror"
                                                id="tipe" name="tipe" required>
                                                <option value="">-- Pilih Kategori --</option>
                                                <option value="TPS"
                                                    {{ old('tipe', $lokasiTps->tipe) == 'TPS' ? 'selected' : '' }}>
                                                    TPS (Tempat Pembuangan Sampah)
                                                </option>
                                                <option value="TPST"
                                                    {{ old('tipe', $lokasiTps->tipe) == 'TPST' ? 'selected' : '' }}>
                                                    TPST (Tempat Pembuangan Sampah Terpadu)
                                                </option>
                                                <option value="TPA"
                                                    {{ old('tipe', $lokasiTps->tipe) == 'TPA' ? 'selected' : '' }}>
                                                    TPA (Tempat Pembuangan Akhir)
                                                </option>
                                            </select>
                                            @error('tipe')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>


                                        <div class="form-group">
                                            <label for="province_id">Provinsi <span class="text-danger">*</span></label>
                                            <select
                                                class="form-control select2-searchable @error('province_id') is-invalid @enderror"
                                                id="province_id" name="province_id" required>
                                                <option value="">-- Pilih Provinsi --</option>
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province->id }}"
                                                        {{ old('province_id', $lokasiTps->province_id) == $province->id ? 'selected' : '' }}>
                                                        {{ $province->name }}
                                                        @if (old('province_id', $lokasiTps->province_id) == $province->id)
                                                            <span class="current-location-badge">Saat ini</span>
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('province_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="regency_id">Kabupaten/Kota <span
                                                    class="text-danger">*</span></label>
                                            <select
                                                class="form-control select2-searchable @error('regency_id') is-invalid @enderror"
                                                id="regency_id" name="regency_id" required>
                                                <option value="">-- Pilih Kabupaten/Kota --</option>
                                                @foreach ($regencies as $regency)
                                                    <option value="{{ $regency->id }}"
                                                        {{ old('regency_id', $lokasiTps->regency_id) == $regency->id ? 'selected' : '' }}>
                                                        {{ $regency->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('regency_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="district_id">Kecamatan <span class="text-danger">*</span></label>
                                            <select
                                                class="form-control select2-searchable @error('district_id') is-invalid @enderror"
                                                id="district_id" name="district_id" required>
                                                <option value="">-- Pilih Kecamatan --</option>
                                                @foreach ($districts as $district)
                                                    <option value="{{ $district->id }}"
                                                        {{ old('district_id', $lokasiTps->district_id) == $district->id ? 'selected' : '' }}>
                                                        {{ $district->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('district_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="village_id">Desa/Kelurahan <span
                                                    class="text-danger">*</span></label>
                                            <select
                                                class="form-control select2-searchable @error('village_id') is-invalid @enderror"
                                                id="village_id" name="village_id" required>
                                                <option value="">-- Pilih Desa/Kelurahan --</option>
                                                @foreach ($villages as $village)
                                                    <option value="{{ $village->id }}"
                                                        {{ old('village_id', $lokasiTps->village_id) == $village->id ? 'selected' : '' }}>
                                                        {{ $village->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('village_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Location Info Display -->
                                        <div class="location-info">
                                            <h6><i class="fas fa-map-marker-alt"></i> Lokasi TPS Saat Ini</h6>
                                            <div id="current-location-info">
                                                <strong>{{ $lokasiTps->nama_lokasi }}</strong><br>
                                                <small>
                                                    <i class="fas fa-globe"></i> {{ $lokasiTps->latitude }},
                                                    {{ $lokasiTps->longitude }}<br>
                                                    <i class="fas fa-map"></i> {{ $lokasiTps->village->name ?? '' }},
                                                    {{ $lokasiTps->district->name ?? '' }},
                                                    {{ $lokasiTps->regency->name ?? '' }},
                                                    {{ $lokasiTps->province->name ?? '' }}
                                                </small>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Pilih Lokasi pada Peta <span class="text-danger">*</span></label>

                                            <div class="search-container">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="search-input"
                                                        placeholder="Cari lokasi (alamat, nama tempat, atau koordinat)">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-primary"
                                                            id="search-button">
                                                            <i class="fas fa-search"></i> Cari
                                                        </button>
                                                        <button type="button" class="btn btn-success"
                                                            id="center-current-location"
                                                            title="Tampilkan lokasi saat ini">
                                                            <i class="fas fa-crosshairs"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <small class="form-text text-muted">
                                                    Masukkan nama lokasi, alamat, atau koordinat (contoh: -7.0563, 110.4542)
                                                </small>
                                            </div>

                                            <div id="map"></div>

                                            <div class="coordinates-display">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-0">
                                                            <label for="latitude">Latitude <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text"
                                                                class="form-control @error('latitude') is-invalid @enderror"
                                                                id="latitude" name="latitude"
                                                                value="{{ old('latitude', $lokasiTps->latitude) }}"
                                                                required>
                                                            @error('latitude')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-0">
                                                            <label for="longitude">Longitude <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text"
                                                                class="form-control @error('longitude') is-invalid @enderror"
                                                                id="longitude" name="longitude"
                                                                value="{{ old('longitude', $lokasiTps->longitude) }}"
                                                                required>
                                                            @error('longitude')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted mt-2">
                                                <i class="fas fa-info-circle"></i> Klik pada peta untuk menentukan lokasi
                                                TPS, cari lokasi menggunakan kotak pencarian, atau masukkan koordinat secara
                                                manual
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <a href="{{ route('lokasi-tps.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
        integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <!-- Leaflet Geocoder JS -->
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>
        $(function() {
            // Konfigurasi Select2 dengan bahasa Indonesia
            const select2Config = {
                theme: 'bootstrap4',
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function() {
                        return "Data tidak ditemukan";
                    },
                    searching: function() {
                        return "Mencari...";
                    },
                    loadingMore: function() {
                        return "Memuat lebih banyak...";
                    },
                    inputTooShort: function(args) {
                        return "Masukkan minimal " + args.minimum + " karakter";
                    },
                    errorLoading: function() {
                        return "Tidak dapat memuat data";
                    }
                },
                escapeMarkup: function(markup) {
                    return markup;
                }
            };

            // Initialize Select2 for all dropdown elements with search functionality
            function initializeSelect2() {
                $('#province_id').select2({
                    ...select2Config,
                    placeholder: '-- Pilih Provinsi --',
                    minimumInputLength: 0
                });

                $('#regency_id').select2({
                    ...select2Config,
                    placeholder: '-- Pilih Kabupaten/Kota --',
                    minimumInputLength: 0
                });

                $('#district_id').select2({
                    ...select2Config,
                    placeholder: '-- Pilih Kecamatan --',
                    minimumInputLength: 0
                });

                $('#village_id').select2({
                    ...select2Config,
                    placeholder: '-- Pilih Desa/Kelurahan --',
                    minimumInputLength: 0
                });

                $('#tipe').select2({
                    ...select2Config,
                    placeholder: '-- Pilih Kategori --',
                    minimumInputLength: 0
                });
            }

            // Initialize Select2 on page load
            initializeSelect2();

            // Initialize map with location from the database
            const initialLat = {{ old('latitude', $lokasiTps->latitude) }};
            const initialLng = {{ old('longitude', $lokasiTps->longitude) }};

            const map = L.map('map').setView([initialLat, initialLng], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);

            // Tambahkan kontrol pencarian
            const geocoder = L.Control.geocoder({
                defaultMarkGeocode: false
            }).addTo(map);

            // Create custom icon for current TPS location
            const tpsIcon = L.divIcon({
                className: 'custom-tps-icon',
                html: `<div style="
                    background-color: #dc3545;
                    width: 30px;
                    height: 30px;
                    border-radius: 50% 50% 50% 0;
                    border: 3px solid #fff;
                    box-shadow: 0 2px 10px rgba(220,53,69,0.5);
                    transform: rotate(-45deg);
                    position: relative;
                ">
                    <div style="
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%) rotate(45deg);
                        color: white;
                        font-size: 16px;
                        font-weight: bold;
                    ">📍</div>
                </div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 30]
            });

            // Inisialisasi marker dengan posisi dari database
            let marker = L.marker([initialLat, initialLng], {
                icon: tpsIcon
            }).addTo(map);

            // Add popup with TPS information
            marker.bindPopup(`
                <div style="text-align: center;">
                    <h6 style="margin-bottom: 8px; color: #dc3545;">
                        <i class="fas fa-trash-alt"></i> {{ $lokasiTps->nama_lokasi }}
                    </h6>
                    <p style="margin-bottom: 5px; font-size: 12px;">
                        <strong>Tipe:</strong> {{ $lokasiTps->tipe }}<br>
                        <strong>Koordinat:</strong> {{ $lokasiTps->latitude }}, {{ $lokasiTps->longitude }}
                    </p>
                    <small style="color: #666;">
                        {{ $lokasiTps->village->name ?? '' }}, {{ $lokasiTps->district->name ?? '' }}<br>
                        {{ $lokasiTps->regency->name ?? '' }}, {{ $lokasiTps->province->name ?? '' }}
                    </small>
                </div>
            `);

            // Variables for user location
            let userLocationMarker = null;

            // Fungsi untuk mendapatkan dan menampilkan lokasi pengguna
            function getUserLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const userLat = position.coords.latitude;
                            const userLng = position.coords.longitude;
                            const accuracy = position.coords.accuracy;

                            // Buat marker untuk lokasi pengguna
                            const userIcon = L.divIcon({
                                className: 'user-location-icon',
                                html: `<div style="
                                    background-color: #007bff;
                                    width: 20px;
                                    height: 20px;
                                    border-radius: 50%;
                                    border: 3px solid #fff;
                                    box-shadow: 0 0 10px rgba(0,123,255,0.5);
                                    position: relative;
                                ">
                                    <div style="
                                        position: absolute;
                                        top: 50%;
                                        left: 50%;
                                        transform: translate(-50%, -50%);
                                        width: 8px;
                                        height: 8px;
                                        background-color: #fff;
                                        border-radius: 50%;
                                    "></div>
                                </div>`,
                                iconSize: [20, 20],
                                iconAnchor: [10, 10]
                            });

                            // // Hapus marker lokasi pengguna sebelumnya jika ada
                            // if (userLocationMarker) {
                            //     map.removeLayer(userLocationMarker);
                            // }

                            // // Tambahkan marker lokasi pengguna
                            // userLocationMarker = L.marker([userLat, userLng], {
                            //     icon: userIcon
                            // }).addTo(map);

                            // userLocationMarker.bindPopup(`
                        //     <b>📍 Lokasi Anda</b><br>
                        //     Koordinat: ${userLat.toFixed(6)}, ${userLng.toFixed(6)}<br>
                        //     Akurasi: ±${Math.round(accuracy)} meter<br>
                        //     <button onclick="useMyLocation(${userLat}, ${userLng})" class="btn btn-sm btn-primary mt-2">
                        //         Gunakan Lokasi Ini
                        //     </button>
                        // `);

                            // // Tambahkan circle untuk menunjukkan akurasi
                            // L.circle([userLat, userLng], {
                            //     radius: accuracy,
                            //     color: '#007bff',
                            //     fillColor: '#007bff',
                            //     fillOpacity: 0.1,
                            //     weight: 1
                            // }).addTo(map);

                        },
                        function(error) {
                            console.log("Error mendapatkan lokasi:", error.message);
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 300000
                        }
                    );
                }
            }

            // Panggil fungsi untuk mendapatkan lokasi pengguna
            getUserLocation();

            // Fungsi untuk menggunakan lokasi pengguna sebagai marker utama
            window.useMyLocation = function(lat, lng) {
                const userLat = parseFloat(lat).toFixed(6);
                const userLng = parseFloat(lng).toFixed(6);

                // Update form inputs
                $('#latitude').val(userLat);
                $('#longitude').val(userLng);

                // Update marker position
                marker.setLatLng([lat, lng]);

                // Center map on the location
                map.setView([lat, lng], 16);

                // Close popup
                if (userLocationMarker) {
                    userLocationMarker.closePopup();
                }

                alert('Lokasi Anda telah digunakan sebagai titik marker TPS.');
            };

            // Handle map click
            map.on('click', function(e) {
                const lat = e.latlng.lat.toFixed(6);
                const lng = e.latlng.lng.toFixed(6);

                // Update form inputs
                $('#latitude').val(lat);
                $('#longitude').val(lng);

                // Update marker position
                marker.setLatLng(e.latlng);
            });

            // Center to current TPS location
            $('#center-current-location').click(function() {
                map.setView([initialLat, initialLng], 16);
                marker.openPopup();
            });

            // Tambahkan event listener untuk kotak pencarian manual
            $('#search-button').click(function() {
                searchLocation();
            });

            $('#search-input').keypress(function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    searchLocation();
                }
            });

            // Fungsi pencarian lokasi
            function searchLocation() {
                const searchText = $('#search-input').val().trim();

                if (!searchText) return;

                // Cek apakah input adalah koordinat
                const coordsRegex =
                    /^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/;

                if (coordsRegex.test(searchText)) {
                    // Input adalah koordinat
                    const parts = searchText.split(',');
                    const lat = parseFloat(parts[0].trim());
                    const lng = parseFloat(parts[1].trim());

                    if (!isNaN(lat) && !isNaN(lng)) {
                        const latlng = L.latLng(lat, lng);
                        updateMapAndMarker(latlng);
                        return;
                    }
                }

                // Gunakan geocoder untuk mencari alamat
                geocoder.geocode(searchText, function(results) {
                    if (results.length > 0) {
                        const latlng = results[0].center;
                        updateMapAndMarker(latlng);

                        // Tampilkan popup dengan informasi hasil pencarian
                        marker.bindPopup(results[0].name).openPopup();
                    } else {
                        alert('Lokasi tidak ditemukan. Coba dengan kata kunci lain.');
                    }
                });
            }

            // Fungsi untuk memperbarui peta dan marker
            function updateMapAndMarker(latlng) {
                const lat = latlng.lat.toFixed(6);
                const lng = latlng.lng.toFixed(6);

                $('#latitude').val(lat);
                $('#longitude').val(lng);
                marker.setLatLng(latlng);
                map.setView(latlng, 16);
            }

            // Event untuk geocoder result
            geocoder.on('markgeocode', function(e) {
                const latlng = e.geocode.center;
                updateMapAndMarker(latlng);
            });

            // Juga memungkinkan pengguna untuk memperbarui marker saat nilai dimasukkan manual
            $('#latitude, #longitude').change(function() {
                const lat = parseFloat($('#latitude').val());
                const lng = parseFloat($('#longitude').val());

                if (!isNaN(lat) && !isNaN(lng)) {
                    const newLatLng = L.latLng(lat, lng);
                    marker.setLatLng(newLatLng);
                    map.setView(newLatLng, 15);
                }
            });

            // Tambahan: Tombol untuk kembali ke lokasi pengguna
            L.Control.UserLocation = L.Control.extend({
                onAdd: function(map) {
                    const container = L.DomUtil.create('div',
                        'leaflet-bar leaflet-control leaflet-control-custom');
                    container.style.backgroundColor = 'white';
                    container.style.backgroundImage =
                        "url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxwYXRoIGQ9Im0zIDExIDEzLTkgMS4yIDIuNCA2LjItLjlMMTQgMTdsMiA3LjYtNi0uOUw3IDIxWiIvPjwvc3ZnPg==')";
                    container.style.backgroundSize = '20px 20px';
                    container.style.backgroundPosition = 'center';
                    container.style.backgroundRepeat = 'no-repeat';
                    container.style.width = '34px';
                    container.style.height = '34px';
                    container.style.cursor = 'pointer';
                    container.title = 'Kembali ke lokasi saya';

                    container.onclick = function() {
                        if (userLocationMarker) {
                            map.setView(userLocationMarker.getLatLng(), 16);
                            userLocationMarker.openPopup();
                        } else {
                            getUserLocation();
                        }
                    }

                    return container;
                },

                onRemove: function(map) {
                    // Nothing to do here
                }
            });

            L.control.userLocation = function(opts) {
                return new L.Control.UserLocation(opts);
            }

            L.control.userLocation({
                position: 'topright'
            }).addTo(map);

            // Fungsi untuk menampilkan loading state pada select2
            function showSelect2Loading(selectId, loadingText = 'Memuat...') {
                const $select = $(selectId);
                $select.empty().append(`<option value="">${loadingText}</option>`);
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.trigger('change');
                }
            }

            // Fungsi untuk mengisi data ke select2
            function populateSelect2(selectId, data, placeholder, selectedValue = null) {
                const $select = $(selectId);
                $select.empty().append(`<option value="">${placeholder}</option>`);

                $.each(data, function(key, value) {
                    const selected = selectedValue && selectedValue == value.id ? 'selected' : '';
                    $select.append(`<option value="${value.id}" ${selected}>${value.name}</option>`);
                });

                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.trigger('change');
                }
            }

            // Handle region selects with AJAX
            $('#province_id').change(function() {
                const provinceId = $(this).val();
                if (provinceId) {
                    // Show loading state
                    showSelect2Loading('#regency_id', 'Memuat Kabupaten/Kota...');
                    showSelect2Loading('#district_id', '-- Pilih Kecamatan --');
                    showSelect2Loading('#village_id', '-- Pilih Desa/Kelurahan --');

                    $.ajax({
                        url: "{{ route('lokasi-tps.getRegencies') }}",
                        type: "GET",
                        data: {
                            province_id: provinceId
                        },
                        success: function(data) {
                            populateSelect2('#regency_id', data, '-- Pilih Kabupaten/Kota --');
                            populateSelect2('#district_id', [], '-- Pilih Kecamatan --');
                            populateSelect2('#village_id', [], '-- Pilih Desa/Kelurahan --');
                        },
                        error: function() {
                            populateSelect2('#regency_id', [], 'Error memuat data');
                        }
                    });
                } else {
                    populateSelect2('#regency_id', [], '-- Pilih Kabupaten/Kota --');
                    populateSelect2('#district_id', [], '-- Pilih Kecamatan --');
                    populateSelect2('#village_id', [], '-- Pilih Desa/Kelurahan --');
                }
            });

            $('#regency_id').change(function() {
                const regencyId = $(this).val();
                if (regencyId) {
                    // Show loading state
                    showSelect2Loading('#district_id', 'Memuat Kecamatan...');
                    showSelect2Loading('#village_id', '-- Pilih Desa/Kelurahan --');

                    $.ajax({
                        url: "{{ route('lokasi-tps.getDistricts') }}",
                        type: "GET",
                        data: {
                            regency_id: regencyId
                        },
                        success: function(data) {
                            populateSelect2('#district_id', data, '-- Pilih Kecamatan --');
                            populateSelect2('#village_id', [], '-- Pilih Desa/Kelurahan --');
                        },
                        error: function() {
                            populateSelect2('#district_id', [], 'Error memuat data');
                        }
                    });
                } else {
                    populateSelect2('#district_id', [], '-- Pilih Kecamatan --');
                    populateSelect2('#village_id', [], '-- Pilih Desa/Kelurahan --');
                }
            });

            $('#district_id').change(function() {
                const districtId = $(this).val();
                if (districtId) {
                    // Show loading state
                    showSelect2Loading('#village_id', 'Memuat Desa/Kelurahan...');

                    $.ajax({
                        url: "{{ route('lokasi-tps.getVillages') }}",
                        type: "GET",
                        data: {
                            district_id: districtId
                        },
                        success: function(data) {
                            populateSelect2('#village_id', data, '-- Pilih Desa/Kelurahan --');
                        },
                        error: function() {
                            populateSelect2('#village_id', [], 'Error memuat data');
                        }
                    });
                } else {
                    populateSelect2('#village_id', [], '-- Pilih Desa/Kelurahan --');
                }
            });

            // Event handlers untuk mencegah form submit saat menekan Enter di Select2
            $('.select2-searchable').on('select2:open', function() {
                $('.select2-search__field').on('keydown', function(e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                });
            });

            // Tambahan: Fungsi untuk validasi form sebelum submit
            $('form').on('submit', function(e) {
                const requiredFields = ['nama_lokasi', 'tipe', 'province_id', 'regency_id', 'district_id',
                    'village_id', 'latitude', 'longitude'
                ];
                let isValid = true;
                let firstInvalidField = null;

                requiredFields.forEach(function(field) {
                    const value = $(`#${field}`).val();
                    if (!value || value.trim() === '') {
                        isValid = false;
                        if (!firstInvalidField) {
                            firstInvalidField = field;
                        }
                        $(`#${field}`).addClass('is-invalid');
                    } else {
                        $(`#${field}`).removeClass('is-invalid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    if (firstInvalidField) {
                        $(`#${firstInvalidField}`).focus();
                        if ($(`#${firstInvalidField}`).hasClass('select2-hidden-accessible')) {
                            $(`#${firstInvalidField}`).select2('open');
                        }
                    }
                    alert('Mohon lengkapi semua field yang wajib diisi (bertanda *)');
                    return false;
                }

                // Validasi koordinat
                const lat = parseFloat($('#latitude').val());
                const lng = parseFloat($('#longitude').val());

                if (isNaN(lat) || isNaN(lng)) {
                    e.preventDefault();
                    $('#latitude, #longitude').addClass('is-invalid');
                    alert('Koordinat latitude dan longitude harus berupa angka yang valid');
                    return false;
                }

                if (lat < -90 || lat > 90) {
                    e.preventDefault();
                    $('#latitude').addClass('is-invalid');
                    alert('Latitude harus berada dalam rentang -90 hingga 90');
                    return false;
                }

                if (lng < -180 || lng > 180) {
                    e.preventDefault();
                    $('#longitude').addClass('is-invalid');
                    alert('Longitude harus berada dalam rentang -180 hingga 180');
                    return false;
                }

                return true;
            });

            // Auto-update location info when coordinates change
            $('#latitude, #longitude').on('input', function() {
                const lat = $('#latitude').val();
                const lng = $('#longitude').val();

                if (lat && lng) {
                    $('#current-location-info').html(`
                        <strong>{{ $lokasiTps->nama_lokasi }}</strong><br>
                        <small>
                            <i class="fas fa-globe"></i> ${lat}, ${lng}<br>
                            <i class="fas fa-edit"></i> <em>Koordinat telah diubah</em>
                        </small>
                    `);
                }
            });

            // Tampilkan notifikasi jika lokasi TPS berhasil dimuat
            setTimeout(() => {
                const notification = $(`
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 350px;">
                        <i class="fas fa-check-circle"></i> <strong>Lokasi TPS berhasil dimuat!</strong><br>
                        <small>Peta telah menampilkan lokasi {{ $lokasiTps->nama_lokasi }} saat ini.</small>
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                `);

                $('body').append(notification);

                // Auto close after 5 seconds
                setTimeout(() => {
                    notification.fadeOut(() => notification.remove());
                }, 5000);
            }, 1000);
        });
    </script>
@endpush
