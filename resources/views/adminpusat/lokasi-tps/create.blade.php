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
    </style>
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Lokasi TPS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('lokasi-tps.index') }}">Lokasi TPS</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Form Tambah Lokasi TPS</h3>
                        </div>
                        <form action="{{ route('lokasi-tps.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama_lokasi">Nama Lokasi TPS <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('nama_lokasi') is-invalid @enderror"
                                                id="nama_lokasi" name="nama_lokasi" value="{{ old('nama_lokasi') }}"
                                                required>
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
                                                <option value="TPS" {{ old('tipe') == 'TPS' ? 'selected' : '' }}>TPS
                                                    (Tempat Pembuangan Sampah)</option>
                                                <option value="TPST" {{ old('tipe') == 'TPST' ? 'selected' : '' }}>TPST
                                                    (Tempat Pembuangan Sampah Terpadu)</option>
                                                <option value="TPA" {{ old('tipe') == 'TPA' ? 'selected' : '' }}>TPA
                                                    (Tempat Pembuangan Akhir)</option>
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
                                                        {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                                        {{ $province->name }}
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
                                                @if (old('province_id'))
                                                    @foreach ($regencies as $regency)
                                                        <option value="{{ $regency->id }}"
                                                            {{ old('regency_id') == $regency->id ? 'selected' : '' }}>
                                                            {{ $regency->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
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
                                                @if (old('regency_id'))
                                                    @foreach ($districts as $district)
                                                        <option value="{{ $district->id }}"
                                                            {{ old('district_id') == $district->id ? 'selected' : '' }}>
                                                            {{ $district->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
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
                                                @if (old('district_id'))
                                                    @foreach ($villages as $village)
                                                        <option value="{{ $village->id }}"
                                                            {{ old('village_id') == $village->id ? 'selected' : '' }}>
                                                            {{ $village->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('village_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Pilih Lokasi pada Peta <span class="text-danger">*</span></label>

                                            <div class="search-container">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="search-input"
                                                        placeholder="Cari lokasi (alamat, nama tempat, atau koordinat)">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-primary" id="search-button">
                                                            <i class="fas fa-search"></i> Cari
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
                                                                value="{{ old('latitude') }}" required>
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
                                                                value="{{ old('longitude') }}" required>
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
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan
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
                // ✅ Tambahan untuk Kategori TPS
                $('#tipe').select2({
                    ...select2Config,
                    placeholder: '-- Pilih Kategori --',
                    minimumInputLength: 0
                });
            }

            // Initialize Select2 on page load
            initializeSelect2();

            // Initialize map (default to center of Indonesia, will be updated with user location)
            const map = L.map('map').setView([-7.056325, 110.454250], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);

            // Tambahkan kontrol pencarian
            const geocoder = L.Control.geocoder({
                defaultMarkGeocode: false
            }).addTo(map);

            // Variables for markers
            let marker;
            let userLocationMarker = null;

            // Check if we have old coordinates to restore
            const oldLat = {{ old('latitude') ? old('latitude') : 'null' }};
            const oldLng = {{ old('longitude') ? old('longitude') : 'null' }};

            // Flag to track if we should use geolocation or old coordinates
            let hasOldCoordinates = false;

            if (oldLat && oldLng) {
                hasOldCoordinates = true;
                marker = L.marker([oldLat, oldLng]).addTo(map);
                $('#latitude').val(oldLat);
                $('#longitude').val(oldLng);
                map.setView([oldLat, oldLng], 15);
            }

            // Fungsi untuk mendapatkan dan menampilkan lokasi pengguna
            function getUserLocation() {
                if (navigator.geolocation) {
                    console.log("Mencari lokasi pengguna...");

                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const userLat = position.coords.latitude;
                            const userLng = position.coords.longitude;
                            const accuracy = position.coords.accuracy;

                            console.log(`Lokasi pengguna ditemukan: ${userLat}, ${userLng}`);

                            // Hanya pindahkan view jika tidak ada koordinat lama
                            if (!hasOldCoordinates) {
                                map.setView([userLat, userLng], 15);
                            }

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

                            // Hapus marker lokasi pengguna sebelumnya jika ada
                            if (userLocationMarker) {
                                map.removeLayer(userLocationMarker);
                            }

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

                            let errorMessage = "";
                            switch (error.code) {
                                case error.PERMISSION_DENIED:
                                    errorMessage = "Akses lokasi ditolak oleh pengguna.";
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    errorMessage = "Informasi lokasi tidak tersedia.";
                                    break;
                                case error.TIMEOUT:
                                    errorMessage = "Waktu permintaan lokasi habis.";
                                    break;
                                default:
                                    errorMessage = "Terjadi kesalahan yang tidak diketahui.";
                                    break;
                            }

                            console.log(`Tidak dapat mendapatkan lokasi: ${errorMessage}`);
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 300000
                        }
                    );
                } else {
                    console.log("Geolocation tidak didukung oleh browser ini.");
                }
            }

            // Fungsi untuk menggunakan lokasi pengguna sebagai marker utama
            window.useMyLocation = function(lat, lng) {
                const userLat = parseFloat(lat).toFixed(6);
                const userLng = parseFloat(lng).toFixed(6);

                // Update form inputs
                $('#latitude').val(userLat);
                $('#longitude').val(userLng);

                // Update or create main marker
                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng]).addTo(map);
                }

                // Center map on the location
                map.setView([lat, lng], 16);

                // Close popup
                if (userLocationMarker) {
                    userLocationMarker.closePopup();
                }

                alert('Lokasi Anda telah digunakan sebagai titik marker.');
            };

            // Panggil fungsi untuk mendapatkan lokasi pengguna
            getUserLocation();

            // Handle map click
            map.on('click', function(e) {
                const lat = e.latlng.lat.toFixed(6);
                const lng = e.latlng.lng.toFixed(6);

                // Update form inputs
                $('#latitude').val(lat);
                $('#longitude').val(lng);

                // Update or create marker
                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }
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
                        if (marker) {
                            marker.bindPopup(results[0].name).openPopup();
                        }
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

                if (marker) {
                    marker.setLatLng(latlng);
                } else {
                    marker = L.marker(latlng).addTo(map);
                }

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

                    if (marker) {
                        marker.setLatLng(newLatLng);
                    } else {
                        marker = L.marker(newLatLng).addTo(map);
                    }

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

            // Pemilihan wilayah saat load halaman (untuk kasus validasi gagal)
            if ($('#province_id').val()) {
                $('#province_id').trigger('change');

                // Tunggu sebentar agar data regency diload
                setTimeout(() => {
                    if ({{ old('regency_id') ? old('regency_id') : 'null' }}) {
                        $('#regency_id').val({{ old('regency_id') ? old('regency_id') : 'null' }}).trigger(
                            'change');

                        // Tunggu sebentar agar data district diload
                        setTimeout(() => {
                            if ({{ old('district_id') ? old('district_id') : 'null' }}) {
                                $('#district_id').val(
                                        {{ old('district_id') ? old('district_id') : 'null' }})
                                    .trigger('change');

                                // Tunggu sebentar agar data village diload
                                setTimeout(() => {
                                    if (
                                        {{ old('village_id') ? old('village_id') : 'null' }}
                                    ) {
                                        $('#village_id').val(
                                            {{ old('village_id') ? old('village_id') : 'null' }}
                                        );
                                    }
                                }, 1000);
                            }
                        }, 1000);
                    }
                }, 1000);
            }

            // Event handlers untuk mencegah form submit saat menekan Enter di Select2
            $('.select2-searchable').on('select2:open', function() {
                $('.select2-search__field').on('keydown', function(e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                });
            });

            // Tambahan: Fungsi untuk clear semua pilihan regional
            window.clearRegionalSelections = function() {
                $('#province_id').val('').trigger('change');
                populateSelect2('#regency_id', [], '-- Pilih Kabupaten/Kota --');
                populateSelect2('#district_id', [], '-- Pilih Kecamatan --');
                populateSelect2('#village_id', [], '-- Pilih Desa/Kelurahan --');
            };

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
        });
    </script>
@endpush
