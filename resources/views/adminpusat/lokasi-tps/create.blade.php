@extends('layouts.app')

@push('css')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
    <style>
        #map {
            height: 400px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .coordinates-display {
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #dee2e6;
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
                                            <label for="nama_lokasi">Nama Lokasi TPS <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('nama_lokasi') is-invalid @enderror" id="nama_lokasi" name="nama_lokasi" value="{{ old('nama_lokasi') }}" required>
                                            @error('nama_lokasi')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="province_id">Provinsi <span class="text-danger">*</span></label>
                                            <select class="form-control @error('province_id') is-invalid @enderror" id="province_id" name="province_id" required>
                                                <option value="">-- Pilih Provinsi --</option>
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                                        {{ $province->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('province_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="regency_id">Kabupaten/Kota <span class="text-danger">*</span></label>
                                            <select class="form-control @error('regency_id') is-invalid @enderror" id="regency_id" name="regency_id" required>
                                                <option value="">-- Pilih Kabupaten/Kota --</option>
                                            </select>
                                            @error('regency_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="district_id">Kecamatan <span class="text-danger">*</span></label>
                                            <select class="form-control @error('district_id') is-invalid @enderror" id="district_id" name="district_id" required>
                                                <option value="">-- Pilih Kecamatan --</option>
                                            </select>
                                            @error('district_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="village_id">Desa/Kelurahan <span class="text-danger">*</span></label>
                                            <select class="form-control @error('village_id') is-invalid @enderror" id="village_id" name="village_id" required>
                                                <option value="">-- Pilih Desa/Kelurahan --</option>
                                            </select>
                                            @error('village_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Pilih Lokasi pada Peta <span class="text-danger">*</span></label>
                                            <div id="map"></div>

                                            <div class="coordinates-display">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-0">
                                                            <label for="latitude">Latitude <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude') }}" required readonly>
                                                            @error('latitude')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-0">
                                                            <label for="longitude">Longitude <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude') }}" required readonly>
                                                            @error('longitude')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted mt-2">
                                                <i class="fas fa-info-circle"></i> Klik pada peta untuk menentukan lokasi TPS
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
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>

    <script>
        $(function () {
            // Initialize map (default to center of Indonesia)
            const map = L.map('map').setView([-7.056325, 110.454250], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);

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

            // Handle region selects with AJAX
            $('#province_id').change(function() {
                const provinceId = $(this).val();
                if (provinceId) {
                    $.ajax({
                        url: "{{ route('lokasi-tps.getRegencies') }}",
                        type: "GET",
                        data: { province_id: provinceId },
                        success: function(data) {
                            $('#regency_id').empty().append('<option value="">-- Pilih Kabupaten/Kota --</option>');
                            $('#district_id').empty().append('<option value="">-- Pilih Kecamatan --</option>');
                            $('#village_id').empty().append('<option value="">-- Pilih Desa/Kelurahan --</option>');

                            $.each(data, function(key, value) {
                                $('#regency_id').append('<option value="' + value.id + '">' + value.name + '</option>');
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
                        data: { regency_id: regencyId },
                        success: function(data) {
                            $('#district_id').empty().append('<option value="">-- Pilih Kecamatan --</option>');
                            $('#village_id').empty().append('<option value="">-- Pilih Desa/Kelurahan --</option>');

                            $.each(data, function(key, value) {
                                $('#district_id').append('<option value="' + value.id + '">' + value.name + '</option>');
