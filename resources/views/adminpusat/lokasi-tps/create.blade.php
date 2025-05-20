@extends('layouts.app')

@push('css')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
        integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
    <!-- Leaflet Geocoder CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
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

                                        <!-- Tambahkan form group untuk level TPS -->
                                        <div class="form-group">
                                            <label for="level">Kategori TPS <span class="text-danger">*</span></label>
                                            <select class="form-control @error('level') is-invalid @enderror" id="level"
                                                name="level" required>
                                                <option value="">-- Pilih Kategori --</option>
                                                <option value="0" {{ old('level') == '0' ? 'selected' : '' }}>TPS
                                                    (Tempat Pembuangan Sampah)</option>
                                                <option value="1" {{ old('level') == '1' ? 'selected' : '' }}>TPST
                                                    (Tempat Pembuangan Sampah Terpadu)</option>
                                                <option value="2" {{ old('level') == '2' ? 'selected' : '' }}>TPA
                                                    (Tempat Pembuangan Akhir)</option>
                                            </select>
                                            @error('level')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="province_id">Provinsi <span class="text-danger">*</span></label>
                                            <select class="form-control @error('province_id') is-invalid @enderror"
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
                                            <select class="form-control @error('regency_id') is-invalid @enderror"
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
                                            <select class="form-control @error('district_id') is-invalid @enderror"
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
                                            <select class="form-control @error('village_id') is-invalid @enderror"
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
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
        integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <!-- Leaflet Geocoder JS -->
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>
        $(function() {
            // Initialize map (default to center of Indonesia)
            const map = L.map('map').setView([-7.056325, 110.454250], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);

            // Tambahkan kontrol pencarian
            const geocoder = L.Control.geocoder({
                defaultMarkGeocode: false
            }).addTo(map);

            // Inisialisasi marker
            let marker;

            // Check if we have old coordinates to restore
            const oldLat = {{ old('latitude') ? old('latitude') : 'null' }};
            const oldLng = {{ old('longitude') ? old('longitude') : 'null' }};

            if (oldLat && oldLng) {
                marker = L.marker([oldLat, oldLng]).addTo(map);
                $('#latitude').val(oldLat);
                $('#longitude').val(oldLng);
                map.setView([oldLat, oldLng], 15);
            }

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

            // Handle region selects with AJAX
            $('#province_id').change(function() {
                const provinceId = $(this).val();
                if (provinceId) {
                    $.ajax({
                        url: "{{ route('lokasi-tps.getRegencies') }}",
                        type: "GET",
                        data: {
                            province_id: provinceId
                        },
                        success: function(data) {
                            $('#regency_id').empty().append(
                                '<option value="">-- Pilih Kabupaten/Kota --</option>');
                            $('#district_id').empty().append(
                                '<option value="">-- Pilih Kecamatan --</option>');
                            $('#village_id').empty().append(
                                '<option value="">-- Pilih Desa/Kelurahan --</option>');

                            $.each(data, function(key, value) {
                                $('#regency_id').append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#regency_id').empty().append('<option value="">-- Pilih Kabupaten/Kota --</option>');
                    $('#district_id').empty().append('<option value="">-- Pilih Kecamatan --</option>');
                    $('#village_id').empty().append('<option value="">-- Pilih Desa/Kelurahan --</option>');
                }
            });

            $('#regency_id').change(function() {
                const regencyId = $(this).val();
                if (regencyId) {
                    $.ajax({
                        url: "{{ route('lokasi-tps.getDistricts') }}",
                        type: "GET",
                        data: {
                            regency_id: regencyId
                        },
                        success: function(data) {
                            $('#district_id').empty().append(
                                '<option value="">-- Pilih Kecamatan --</option>');
                            $('#village_id').empty().append(
                                '<option value="">-- Pilih Desa/Kelurahan --</option>');

                            $.each(data, function(key, value) {
                                $('#district_id').append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#district_id').empty().append('<option value="">-- Pilih Kecamatan --</option>');
                    $('#village_id').empty().append('<option value="">-- Pilih Desa/Kelurahan --</option>');
                }
            });

            $('#district_id').change(function() {
                const districtId = $(this).val();
                if (districtId) {
                    $.ajax({
                        url: "{{ route('lokasi-tps.getVillages') }}",
                        type: "GET",
                        data: {
                            district_id: districtId
                        },
                        success: function(data) {
                            $('#village_id').empty().append(
                                '<option value="">-- Pilih Desa/Kelurahan --</option>');

                            $.each(data, function(key, value) {
                                $('#village_id').append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#village_id').empty().append('<option value="">-- Pilih Desa/Kelurahan --</option>');
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
                                        {{ old('village_id') ? old('village_id') : 'null' }}) {
                                        $('#village_id').val(
                                            {{ old('village_id') ? old('village_id') : 'null' }}
                                            );
                                    }
                                }, 500);
                            }
                        }, 500);
                    }
                }, 500);
            }
        });
    </script>
@endpush
