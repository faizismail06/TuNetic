@extends('components.navbar')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Profil Admin TPST</h5>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Form action is dynamic based on user level --}}
                        @php
                            $formRoute = '';
                            $ajaxPhotoRoute = '';
                            $regenciesRoute = '';
                            $districtsRoute = '';
                            $villagesRoute = '';

                            if ($user->level === 1) {
                                $formRoute = route('admin.profile.update');
                                $ajaxPhotoRoute = route('admin.profile.upload-photo');
                                $regenciesRoute = 'admin.get.regencies';
                                $districtsRoute = 'admin.get.districts';
                                $villagesRoute = 'admin.get.villages';
                            } elseif ($user->level === 2) {
                                $formRoute = route('admin_tpst.profile.update');
                                $ajaxPhotoRoute = route('admin_tpst.profile.upload-photo');
                                $regenciesRoute = 'admin_tpst.get.regencies';
                                $districtsRoute = 'admin_tpst.get.districts';
                                $villagesRoute = 'admin_tpst.get.villages';
                            } else {
                                // Fallback for other user types
                                $formRoute = route('profile.update');
                                $ajaxPhotoRoute = route('profile.upload-photo');
                                $regenciesRoute = 'get.regencies';
                                $districtsRoute = 'get.districts';
                                $villagesRoute = 'get.villages';
                            }
                        @endphp

                        <form method="POST" action="{{ $formRoute }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Profile Photo Section -->
                            <div class="row mb-4 align-items-center">
                                <div class="col-md-3 text-center">
                                    <label class="form-label mb-2 ms-3" style="text-align: left; display: block;">Foto
                                        Diri</label>
                                    <div class="profile-image-container mb-3">
                                        <div class="profile-image-wrapper rounded-circle mx-auto d-flex align-items-center justify-content-center"
                                            style="width: 150px; height: 150px; overflow: hidden; background-color: #f8f9fa; cursor: pointer;"
                                            id="profile-image-clickable">
                                            @if ($user && $user->foto)
                                                <img id="profile-preview" src="{{ asset('storage/profil/' . $user->foto) }}"
                                                    class="img-fluid w-100 h-100" style="object-fit: cover;"
                                                    alt="Foto Profil">
                                            @else
                                                <div id="profile-preview"
                                                    class="w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-light">
                                                    <i class="fas fa-user text-secondary" style="font-size: 4rem;"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2"
                                            style="transform: translate(-10px, -10px);">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-9">
                                    <div class="d-flex flex-column h-100 justify-content-center">
                                        <div class="d-flex align-items-center mb-2">
                                            <button type="button" id="btn-choose-photo" class="btn btn-success px-4"
                                                style="background-color: #299E63; border-color: #299E63;">
                                                <i class="fas fa-camera me-2"></i> Pilih Photo
                                            </button>
                                            <input type="file" name="gambar" id="input-gambar" class="d-none"
                                                accept="image/jpeg,image/png,image/jpg">
                                        </div>
                                        <div class="text-muted small">
                                            <i class="fas fa-info-circle me-1"></i> Gambar Profile Anda sebaiknya memiliki
                                            rasio 1:1 dan berukuran tidak lebih dari 2MB.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Personal Information -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="name" class="form-label">Nama Lengkap <span
                                            class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $user->name ?? '') }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="email" class="form-label">Email</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="email"
                                        class="form-control @error('email') is-invalid @enderror colored-input"
                                        id="email" name="email" value="{{ old('email', $user->email ?? '') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="no_telepon" class="form-label">No Telepon <span
                                            class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                                        id="no_telepon" name="no_telepon"
                                        value="{{ old('no_telepon', $user->no_telepon ?? '') }}">
                                    @error('no_telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Wilayah Section -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="province" class="form-label">Provinsi <span
                                            class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <select name="province_id" id="province"
                                        class="form-control @error('province_id') is-invalid @enderror">
                                        <option value="">Pilih Provinsi</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}"
                                                {{ old('province_id', $user->province_id ?? '') == $province->id ? 'selected' : '' }}>
                                                {{ $province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('province_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="regency" class="form-label">Kabupaten/Kota <span
                                            class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <select name="regency_id" id="regency"
                                        class="form-control @error('regency_id') is-invalid @enderror">
                                        <option value="">Pilih Kabupaten/Kota</option>
                                        @if ($user && $user->regency_id)
                                            <option value="{{ $user->regency_id }}" selected>
                                                {{ $user->regency->name ?? '' }}</option>
                                        @endif
                                    </select>
                                    @error('regency_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="district" class="form-label">Kecamatan <span
                                            class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <select name="district_id" id="district"
                                        class="form-control @error('district_id') is-invalid @enderror">
                                        <option value="">Pilih Kecamatan</option>
                                        @if ($user && $user->district_id)
                                            <option value="{{ $user->district_id }}" selected>
                                                {{ $user->district->name ?? '' }}</option>
                                        @endif
                                    </select>
                                    @error('district_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="village" class="form-label">Desa/Kelurahan <span
                                            class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <select name="village_id" id="village"
                                        class="form-control @error('village_id') is-invalid @enderror">
                                        <option value="">Pilih Desa/Kelurahan</option>
                                        @if ($user && $user->village_id)
                                            <option value="{{ $user->village_id }}" selected>
                                                {{ $user->village->name ?? '' }}</option>
                                        @endif
                                    </select>
                                    @error('village_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Setelah bagian Desa/Kelurahan -->

                            <div class="row mt-4">
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-success px-4"
                                        style="background-color: #299E63; border-color: #299E63;">
                                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-image-wrapper {
            border: 3px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .profile-image-wrapper:hover {
            border-color: #20c997;
        }

        #btn-choose-photo {
            transition: all 0.2s ease;
        }

        #btn-choose-photo:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .card {
            border-radius: 0.5rem;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
        }

        .alert {
            border-radius: 0.5rem;
        }

        select.form-select {
            width: 100%;
        }

        .colored-input {
            background-color: #d4edda;
            /* Warna hijau muda seperti contoh */
            border: 1px solid #c3e6cb;
            color: #155724;
            border-radius: 0.25rem;
            /* Sesuaikan radius sesuai keinginan */
            padding: 0.375rem 0.75rem;
            /* Sesuaikan padding sesuai keinginan */
        }

        .colored-input:focus {
            background-color: #d4edda;
            /* Pertahankan warna saat fokus */
            border-color: #c3e6cb;
            box-shadow: 0 0 0 0.2rem rgba(207, 233, 210, 0.5);
            /* Efek fokus yang lebih halus */
            color: #155724;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Auto trigger file input when button clicked
            $('#btn-choose-photo').click(function() {
                $('#input-gambar').trigger('click');
            });

            // Preview and auto-upload when file selected
            $('#input-gambar').change(function() {
                if (this.files && this.files[0]) {
                    // Validate file size
                    if (this.files[0].size > 2 * 1024 * 1024) {
                        toastr.error('Ukuran file maksimal 2MB');
                        return;
                    }

                    // Preview image
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#profile-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);

                    // Auto upload
                    uploadPhoto(this.files[0]);
                }
            });

            // Upload function
            function uploadPhoto(file) {
                let formData = new FormData();
                formData.append('gambar', file);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: '{{ $ajaxPhotoRoute }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#btn-choose-photo').html(
                            '<i class="fas fa-spinner fa-spin me-2"></i> Mengupload...').prop(
                            'disabled', true);
                    },
                    complete: function() {
                        $('#btn-choose-photo').html('<i class="fas fa-camera me-2"></i> Pilih Photo')
                            .prop('disabled', false);
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Foto profil berhasil diupdate!');
                            if (response.foto_url) {
                                $('#profile-preview').attr('src', response.foto_url + '?' + new Date()
                                    .getTime());
                            }
                        }
                    },
                    error: function(xhr) {
                        let error = xhr.responseJSON?.error || 'Gagal mengupload foto';
                        toastr.error(error);
                        $('#input-gambar').val('');
                    }
                });
            }

            // Wilayah Dropdown Logic
            function loadRegencies(provinceId, selectedId = null) {
                if (!provinceId) {
                    $('#regency').html('<option value="">Pilih Kabupaten/Kota</option>');
                    $('#district').html('<option value="">Pilih Kecamatan</option>');
                    $('#village').html('<option value="">Pilih Desa/Kelurahan</option>');
                    return;
                }

                $.ajax({
                    url: '{{ route($regenciesRoute, ['province_id' => ':province_id']) }}'.replace(
                        ':province_id', provinceId),
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
                        $('#regency').html(options);

                        if (selectedId) {
                            $('#regency').trigger('change');
                        }
                    },
                    error: function(xhr) {
                        console.error('Gagal memuat data kabupaten/kota', xhr);
                        $('#regency').html('<option value="">Error: Gagal memuat data</option>');
                    }
                });
            }

            function loadDistricts(regencyId, selectedId = null) {
                if (!regencyId) {
                    $('#district').html('<option value="">Pilih Kecamatan</option>');
                    $('#village').html('<option value="">Pilih Desa/Kelurahan</option>');
                    return;
                }

                $.ajax({
                    url: '{{ route($districtsRoute, ['regency_id' => ':regency_id']) }}'.replace(
                        ':regency_id', regencyId),
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
                        $('#district').html(options);

                        if (selectedId) {
                            $('#district').trigger('change');
                        }
                    },
                    error: function(xhr) {
                        console.error('Gagal memuat data kecamatan', xhr);
                        $('#district').html('<option value="">Error: Gagal memuat data</option>');
                    }
                });
            }

            function loadVillages(districtId, selectedId = null) {
                if (!districtId) {
                    $('#village').html('<option value="">Pilih Desa/Kelurahan</option>');
                    return;
                }

                $.ajax({
                    url: '{{ route($villagesRoute, ['district_id' => ':district_id']) }}'.replace(
                        ':district_id', districtId),
                    type: 'GET',
                    dataType: 'json',
                    cache: false,
                    success: function(data) {
                        console.log('Village data received:', data);
                        console.log('Selected village ID:', selectedId);

                        var options = '<option value="">Pilih Desa/Kelurahan</option>';
                        $.each(data, function(key, value) {
                            var selected = (selectedId && value.id == selectedId) ? 'selected' :
                                '';
                            options += '<option value="' + value.id + '" ' + selected + '>' +
                                value.name + '</option>';
                        });
                        $('#village').html(options);

                        // Tambahan: Force select jika ada selectedId
                        if (selectedId) {
                            $('#village').val(selectedId);
                            console.log('Forcing select village ID:', selectedId);
                        }
                    },
                    error: function(xhr) {
                        console.error('Gagal memuat data desa/kelurahan', xhr);
                        $('#village').html('<option value="">Error: Gagal memuat data</option>');
                    }
                });
            }

            // Event handler untuk dropdown
            $('#province').change(function() {
                var provinceId = $(this).val();
                loadRegencies(provinceId);
            });

            $('#regency').change(function() {
                var regencyId = $(this).val();
                loadDistricts(regencyId);
            });

            $('#district').change(function() {
                var districtId = $(this).val();
                loadVillages(districtId);
            });

            // Inisialisasi data saat pertama kali load
            @if ($user && $user->province_id)
                var provinceId = '{{ $user->province_id }}';
                var regencyId = '{{ $user->regency_id }}';
                var districtId = '{{ $user->district_id }}';
                var villageId = '{{ $user->village_id }}';

                console.log('Init data - provinceId:', provinceId);
                console.log('Init data - regencyId:', regencyId);
                console.log('Init data - districtId:', districtId);
                console.log('Init data - villageId:', villageId);

                // Load semua data secara berurutan dengan penanganan yang lebih baik
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

                                    // Sebagai fallback, coba set village value lagi setelah 1 detik
                                    setTimeout(function() {
                                        if ($('#village option[value="' + villageId + '"]')
                                            .length) {
                                            $('#village').val(villageId);
                                            console.log('Village value set via fallback');
                                        } else {
                                            console.log(
                                                'Village option not found in dropdown after fallback'
                                            );
                                        }
                                    }, 1000);
                                }
                            }, 500);
                        }
                    }, 500);
                }
            @endif
        });
    </script>
@endpush