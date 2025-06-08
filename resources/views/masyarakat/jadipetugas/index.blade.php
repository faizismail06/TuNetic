@extends('components.navbar')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card shadow-lg">
                    <div class="card-header text-white" style="background-color: #299E63">
                        <h4 class="mb-0">
                            <i class="fas fa-user-shield" style="font-size: 1.4rem; margin-right: 10px;"></i> Registrasi
                            Petugas Kebersihan
                        </h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('masyarakat.jadi-petugas.submit') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Persyaratan Section with Checkboxes -->
                            <div class="alert alert-light border mb-4">
                                <h5 class="mb-3"><strong>Sebelum Mendaftar, Pastikan Anda :</strong></h5>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="req1" onchange="toggleForm()">
                                    <label class="form-check-label" for="req1">
                                        Usia minimal 18 tahun
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="req2" onchange="toggleForm()">
                                    <label class="form-check-label" for="req2">
                                        Berdomisili di area layanan TuNetic
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="req3" onchange="toggleForm()">
                                    <label class="form-check-label" for="req3">
                                        Siap bekerja sesuai jadwal yang ditentukan
                                    </label>
                                </div>
                                <div class="mt-3">
                                    <small class="text-danger">* Dengan mengisi form ini, Anda menyetujui ketentuan yang
                                        berlaku.</small>
                                </div>
                            </div>

                            <!-- Form Content -->
                            <div id="mainForm" style="opacity: 0.5; pointer-events: none;">

                                <!-- Foto Diri Section -->
                                <div class="mb-4">
                                    <h5 class="mb-3">Foto Diri</h5>
                                    <div class="border rounded p-3 text-center bg-light">
                                        <div class="d-flex justify-content-center mb-2">
                                            <div class="border rounded d-flex flex-column align-items-center justify-content-center"
                                                style="width: 150px; height: 150px; cursor: pointer; background-color: white;"
                                                onclick="document.getElementById('fotoDiriUpload').click()">
                                                <i class="fas fa-camera fa-2x text-muted mb-2"></i>
                                                <span class="text-muted">Pilih Photo</span>
                                            </div>
                                            <input type="file" id="fotoDiriUpload" class="d-none" name="foto_diri"
                                                accept="image/*" required>
                                        </div>
                                        <p class="small text-muted mb-0">
                                            Gambar Profile Anda sebaiknya memiliki rasio 1:1 dan berukuran tidak lebih dari
                                            2MB.
                                        </p>
                                    </div>
                                    <div id="imagePreview" class="text-center mt-3 d-none">
                                        <img id="previewImage" src="#" alt="Preview" class="img-thumbnail"
                                            style="width: 150px; height: 150px; object-fit: cover;">
                                        <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeImage()">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </div>
                                    @error('foto_diri')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Personal Data Section -->
                                <div class="mb-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nama Lengkap *</label>
                                        <input type="text"
                                            class="form-control @error('nama_lengkap') is-invalid @enderror"
                                            name="nama_lengkap" value="{{ old('nama_lengkap', $user->name ?? '') }}"
                                            placeholder="Masukkan Nama Lengkap" required>
                                        @error('nama_lengkap')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="mb-3">
                                        <label class="form-label fw-bold">No Telepon *</label>
                                        <input type="tel" class="form-control @error('no_telepon') is-invalid @enderror"
                                            name="no_telepon" value="{{ old('no_telepon', $user->no_telepon ?? '') }}"
                                            placeholder="Masukkan No Telepon" required>
                                        @error('no_telepon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Email *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email', $user->email ?? '') }}"
                                            placeholder="Masukkan Email Aktif">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Lahir</label>
                                        <input type="date"
                                            class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                            name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                                        @error('tanggal_lahir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <!-- Section Provinsi dan Kabupaten/Kota -->
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Provinsi *</label>
                                            <select class="form-control @error('provinsi_id') is-invalid @enderror"
                                                name="provinsi_id" id="provinsi">
                                                <option value="">Pilih Provinsi</option>
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province->id }}"
                                                        {{ old('provinsi_id') == $province->id ? 'selected' : '' }}>
                                                        {{ $province->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('provinsi_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Kabupaten/Kota *</label>
                                            <select class="form-control @error('kabupaten_id') is-invalid @enderror"
                                                name="kabupaten_id" id="kabupaten">
                                                <option value="">Pilih Kabupaten/Kota</option>
                                                @if (old('kabupaten_id'))
                                                    <option value="{{ old('kabupaten_id') }}" selected>
                                                        {{ old('kabupaten_name') }}</option>
                                                @endif
                                            </select>
                                            @error('kabupaten_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Section Kecamatan dan Desa/Kelurahan -->
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Kecamatan *</label>
                                            <select class="form-control @error('kecamatan_id') is-invalid @enderror"
                                                name="kecamatan_id" id="kecamatan">
                                                <option value="">Pilih Kecamatan</option>
                                                @if (old('kecamatan_id'))
                                                    <option value="{{ old('kecamatan_id') }}" selected>
                                                        {{ old('kecamatan_name') }}</option>
                                                @endif
                                            </select>
                                            @error('kecamatan_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Desa/Kelurahan *</label>
                                            <select class="form-control @error('desa_id') is-invalid @enderror"
                                                name="desa_id" id="desa">
                                                <option value="">Pilih Desa/Kelurahan</option>
                                                @if (old('desa_id'))
                                                    <option value="{{ old('desa_id') }}" selected>{{ old('desa_name') }}
                                                    </option>
                                                @endif
                                            </select>
                                            @error('desa_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- SIM Upload Section -->
                                <div class="mb-4">
                                    <h5 class="mb-3">Upload SIM</h5>
                                    <div class="border rounded p-3 text-center bg-light">
                                        <div class="d-flex justify-content-center mb-2">
                                            <div class="border rounded d-flex flex-column align-items-center justify-content-center"
                                                style="width: 150px; height: 150px; cursor: pointer; background-color: white;"
                                                onclick="document.getElementById('simUpload').click()">
                                                <i class="fas fa-id-card fa-2x text-muted mb-2"></i>
                                                <span class="text-muted">Upload SIM</span>
                                            </div>
                                            <input type="file" id="simUpload" class="d-none" name="foto_sim"
                                                accept="image/jpeg,image/jpg,image/png">
                                        </div>
                                        <p class="small text-muted mb-0">
                                            Unggah bukti berupa foto SIM dalam format JPG/JPEG/PNG (maksimal 2 MB)
                                        </p>
                                    </div>
                                    <div id="simPreview" class="text-center mt-3 d-none">
                                        <img id="previewSim" src="#" alt="Preview SIM" class="img-thumbnail"
                                            style="width: 150px; height: 150px; object-fit: contain;">
                                        <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeSim()">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </div>
                                    @error('foto_sim')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Reason Section -->
                                <div class="mb-4">
                                    <h5 class="mb-3">Alasan Bergabung</h5>
                                    <textarea class="form-control @error('alasan_bergabung') is-invalid @enderror" name="alasan_bergabung" rows="4"
                                        placeholder="Berikan alasan terbaik Anda" required>{{ old('alasan_bergabung') }}</textarea>
                                    @error('alasan_bergabung')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Data Confirmation Section -->
                                <div class="mb-4">
                                    <div class="alert border"
                                        style="background-color: rgba(41, 158, 99, 0.1); border-color: #299E63;">
                                        <div class="form-check d-flex align-items-start">
                                            <input class="form-check-input me-3 mt-1" type="checkbox"
                                                id="dataConfirmation" onchange="toggleSubmitButton()"
                                                style="transform: scale(1.2);">
                                            <label class="form-check-label" for="dataConfirmation">
                                                <strong>Dengan ini, saya menyatakan bahwa data yang saya berikan benar dan
                                                    saya menyetujui semua ketentuan yang berlaku.</strong>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-lg text-white"
                                        style="background-color: #299E63" id="submitBtn">
                                        Kirim Pendaftaran
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to toggle submit button based on data confirmation
        function toggleSubmitButton() {
            const req1 = document.getElementById('req1').checked;
            const req2 = document.getElementById('req2').checked;
            const req3 = document.getElementById('req3').checked;
            const dataConfirmation = document.getElementById('dataConfirmation').checked;
            const submitBtn = document.getElementById('submitBtn');

            // Submit button hanya aktif jika semua requirement dan data confirmation dicentang
            if (req1 && req2 && req3 && dataConfirmation) {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
            } else {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.6';
            }
        }

        // Simple and direct approach
        function toggleForm() {
            const req1 = document.getElementById('req1').checked;
            const req2 = document.getElementById('req2').checked;
            const req3 = document.getElementById('req3').checked;
            const mainForm = document.getElementById('mainForm');

            if (req1 && req2 && req3) {
                // Enable form
                mainForm.style.opacity = '1';
                mainForm.style.pointerEvents = 'auto';
            } else {
                // Disable form
                mainForm.style.opacity = '0.5';
                mainForm.style.pointerEvents = 'none';

                // Clear uploads and uncheck data confirmation
                clearUploads();
                document.getElementById('dataConfirmation').checked = false;
            }

            // Always check submit button status
            toggleSubmitButton();
        }


        function clearUploads() {
            document.getElementById('fotoDiriUpload').value = '';
            document.getElementById('simUpload').value = '';
            document.getElementById('imagePreview').classList.add('d-none');
            document.getElementById('simPreview').classList.add('d-none');
        }

        // Image preview for foto diri
        document.getElementById('fotoDiriUpload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('previewImage').src = event.target.result;
                    document.getElementById('imagePreview').classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });

        function removeImage() {
            document.getElementById('fotoDiriUpload').value = '';
            document.getElementById('imagePreview').classList.add('d-none');
        }

        // Image preview for SIM
        document.getElementById('simUpload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('previewSim').src = event.target.result;
                    document.getElementById('simPreview').classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });

        function removeSim() {
            document.getElementById('simUpload').value = '';
            document.getElementById('simPreview').classList.add('d-none');
        }

        // Dynamic region selection
        document.getElementById('provinsi').addEventListener('change', function() {
            const provinsi = this.value;
            const kabupatenSelect = document.getElementById('kabupaten');

            kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';

            if (provinsi === 'Jawa Tengah') {
                const options = ['Kota Semarang', 'Kabupaten Semarang', 'Kota Salatiga'];
                options.forEach(opt => {
                    const option = document.createElement('option');
                    option.value = opt;
                    option.textContent = opt;
                    kabupatenSelect.appendChild(option);
                });
            }
        });

        // Initialize form state
        window.onload = function() {
            toggleForm();
        };
    </script>
@endsection

@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
            font-size: 1.2rem;
        }

        textarea {
            resize: none;
            min-height: 120px;
        }

        .form-control:focus {
            border-color: #299E63;
            box-shadow: 0 0 0 0.25rem rgba(41, 158, 99, 0.25);
        }

        .alert-light {
            background-color: #f8f9fa;
        }

        h5 {
            color: #299E63;
            font-weight: 600;
        }

        #mainForm {
            transition: all 0.3s ease;
        }
    </style>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Wilayah Dropdown Logic
            function loadRegencies(provinceId, selectedId = null) {
                if (!provinceId) {
                    $('#kabupaten').html('<option value="">Pilih Kabupaten/Kota</option>');
                    $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');
                    $('#desa').html('<option value="">Pilih Desa/Kelurahan</option>');
                    return;
                }

                $.ajax({
                    url: '{{ route('masyarakat.get.regencies', ['province_id' => ':province_id']) }}'
                        .replace(':province_id', provinceId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var options = '<option value="">Pilih Kabupaten/Kota</option>';
                        $.each(data, function(key, value) {
                            var selected = (selectedId && value.id == selectedId) ? 'selected' :
                                '';
                            options += '<option value="' + value.id + '" ' + selected + '>' +
                                value.name + '</option>';
                        });
                        $('#kabupaten').html(options);

                        if (selectedId) {
                            $('#kabupaten').trigger('change');
                        }
                    },
                    error: function(xhr) {
                        console.error('Gagal memuat data kabupaten/kota', xhr);
                        $('#kabupaten').html('<option value="">Error: Gagal memuat data</option>');
                    }
                });
            }

            function loadDistricts(regencyId, selectedId = null) {
                if (!regencyId) {
                    $('#kecamatan').html('<option value="">Pilih Kecamatan</option>');
                    $('#desa').html('<option value="">Pilih Desa/Kelurahan</option>');
                    return;
                }

                $.ajax({
                    url: '{{ route('masyarakat.get.districts', ['regency_id' => ':regency_id']) }}'
                        .replace(':regency_id', regencyId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var options = '<option value="">Pilih Kecamatan</option>';
                        $.each(data, function(key, value) {
                            var selected = (selectedId && value.id == selectedId) ? 'selected' :
                                '';
                            options += '<option value="' + value.id + '" ' + selected + '>' +
                                value.name + '</option>';
                        });
                        $('#kecamatan').html(options);

                        if (selectedId) {
                            $('#kecamatan').trigger('change');
                        }
                    },
                    error: function(xhr) {
                        console.error('Gagal memuat data kecamatan', xhr);
                        $('#kecamatan').html('<option value="">Error: Gagal memuat data</option>');
                    }
                });
            }

            function loadVillages(districtId, selectedId = null) {
                if (!districtId) {
                    $('#desa').html('<option value="">Pilih Desa/Kelurahan</option>');
                    return;
                }

                $.ajax({
                    url: '{{ route('masyarakat.get.villages', ['district_id' => ':district_id']) }}'
                        .replace(':district_id', districtId),
                    type: 'GET',
                    dataType: 'json',
                    cache: false,
                    success: function(data) {
                        var options = '<option value="">Pilih Desa/Kelurahan</option>';
                        $.each(data, function(key, value) {
                            var selected = (selectedId && value.id == selectedId) ? 'selected' :
                                '';
                            options += '<option value="' + value.id + '" ' + selected + '>' +
                                value.name + '</option>';
                        });
                        $('#desa').html(options);

                        if (selectedId) {
                            $('#desa').val(selectedId);
                        }
                    },
                    error: function(xhr) {
                        console.error('Gagal memuat data desa/kelurahan', xhr);
                        $('#desa').html('<option value="">Error: Gagal memuat data</option>');
                    }
                });
            }

            // Event handler untuk dropdown
            $('#provinsi').change(function() {
                var provinceId = $(this).val();
                loadRegencies(provinceId);
            });

            $('#kabupaten').change(function() {
                var regencyId = $(this).val();
                loadDistricts(regencyId);
            });

            $('#kecamatan').change(function() {
                var districtId = $(this).val();
                loadVillages(districtId);
            });

            // Inisialisasi data jika ada data lama (old input)
            @if (old('provinsi_id'))
                var provinceId = '{{ old('provinsi_id') }}';
                var regencyId = '{{ old('kabupaten_id') }}';
                var districtId = '{{ old('kecamatan_id') }}';
                var villageId = '{{ old('desa_id') }}';

                if (provinceId) {
                    loadRegencies(provinceId, regencyId);

                    // Tunggu untuk regencies dimuat
                    setTimeout(function() {
                        if (regencyId) {
                            loadDistricts(regencyId, districtId);

                            // Tunggu untuk districts dimuat
                            setTimeout(function() {
                                if (districtId && villageId) {
                                    loadVillages(districtId, villageId);
                                }
                            }, 500);
                        }
                    }, 500);
                }
            @endif
        });
    </script>
@endpush
