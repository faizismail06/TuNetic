@extends('components.navbar')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Profil Pengguna</h5>
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
                            $formRoute = route('masyarakat.profile.update');
                            $ajaxPhotoRoute = route('masyarakat.profile.upload-photo');
                            $regenciesRoute = 'masyarakat.get.regencies';
                            $districtsRoute = 'masyarakat.get.districts';
                            $villagesRoute = 'masyarakat.get.villages';

                        @endphp

                        <form method="POST" action="{{ $formRoute }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Profile Photo Section -->
                            <div class="row mb-4 align-items-center">
                                <div class="col-md-3 text-center">
                                    <label class="form-label mb-2 ms-3" style="text-align: left; display: block;">Foto Diri</label>
                                    <div class="profile-image-container mb-3 position-relative">
                                        <div class="profile-image-wrapper rounded-circle mx-auto d-flex align-items-center justify-content-center"
                                            style="width: 150px; height: 150px; overflow: hidden; background-color: #f8f9fa; cursor: pointer;"
                                            id="profile-image-clickable">
                                            @if ($user && $user->gambar)
                                                <img id="profile-preview" src="{{ asset('storage/profile/' . $user->gambar) }}"
                                                    class="img-fluid w-100 h-100" style="object-fit: cover;" alt="Foto Profil">
                                            @else
                                                <div id="profile-preview"
                                                    class="w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-light">
                                                    <i class="fas fa-user text-secondary" style="font-size: 4rem;"></i>
                                                </div>
                                            @endif
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
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="alamat" class="form-label">Detail Alamat <span
                                            class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="2">{{ old('alamat', $user->alamat ?? '') }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Contoh: Jl. Merdeka No. 10, RT 05/RW 02</small>
                                </div>
                            </div>

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
            position: relative;
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
            border: 1px solid #c3e6cb;
            color: #155724;
            border-radius: 0.25rem;
            padding: 0.375rem 0.75rem;
        }

        .colored-input:focus {
            background-color: #d4edda;
            border-color: #c3e6cb;
            box-shadow: 0 0 0 0.2rem rgba(207, 233, 210, 0.5);
            color: #155724;
        }

        /* Pastikan gambar preview ditampilkan dengan benar */
        #profile-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #299E63;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Profile image hover effect */
.profile-image-container::after {
    content: '';
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.profile-image-container:hover::after {
    opacity: 1;
}
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toastr configuration
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": 3000,
                "extendedTimeOut": 1000,
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            // Auto trigger file input when button or image clicked
            $('#btn-choose-photo, #profile-image-clickable').click(function(e) {
                e.preventDefault();
                $('#input-gambar').trigger('click');
            });

            // Preview foto when selected
            $('#input-gambar').change(function() {
                const file = this.files[0];
                
                if (!file) {
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    toastr.error('Format file harus JPG, JPEG, atau PNG');
                    this.value = '';
                    return;
                }

                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    toastr.error('Ukuran file maksimal 2MB');
                    this.value = '';
                    return;
                }

                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageUrl = e.target.result;
                    
                    if ($('#profile-preview').is('img')) {
                        $('#profile-preview').attr('src', imageUrl);
                    } else {
                        $('#profile-preview').replaceWith(
                            `<img id="profile-preview" src="${imageUrl}" class="img-fluid w-100 h-100" style="object-fit: cover;" alt="Foto Profil">`
                        );
                    }
                    
                    toastr.success('Foto berhasil dipilih. Klik "Simpan Perubahan" untuk menyimpan.');
                }
                reader.readAsDataURL(file);
            });

            // Wilayah Dropdown Logic
            function loadRegencies(provinceId, selectedId = null) {
                if (!provinceId) {
                    $('#regency').html('<option value="">Pilih Kabupaten/Kota</option>').prop('disabled', false);
                    $('#district').html('<option value="">Pilih Kecamatan</option>').prop('disabled', false);
                    $('#village').html('<option value="">Pilih Desa/Kelurahan</option>').prop('disabled', false);
                    return;
                }

                $('#regency').prop('disabled', true).html('<option value="">Loading...</option>');

                $.ajax({
                    url: '{{ route($regenciesRoute, ["province_id" => ":province_id"]) }}'.replace(':province_id', provinceId),
                    type: 'GET',
                    dataType: 'json',
                    timeout: 10000,
                    success: function(data) {
                        let options = '<option value="">Pilih Kabupaten/Kota</option>';
                        if (Array.isArray(data) && data.length > 0) {
                            $.each(data, function(key, value) {
                                const selected = (selectedId && value.id == selectedId) ? 'selected' : '';
                                options += `<option value="${value.id}" ${selected}>${value.name}</option>`;
                            });
                        }
                        $('#regency').html(options).prop('disabled', false);
                        
                        // Reset district and village
                        $('#district').html('<option value="">Pilih Kecamatan</option>');
                        $('#village').html('<option value="">Pilih Desa/Kelurahan</option>');

                        if (selectedId) {
                            setTimeout(() => $('#regency').trigger('change'), 100);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading regencies:', error);
                        $('#regency').html('<option value="">Error: Gagal memuat data</option>').prop('disabled', false);
                        toastr.error('Gagal memuat data kabupaten/kota. Silakan coba lagi.');
                    }
                });
            }

            function loadDistricts(regencyId, selectedId = null) {
                if (!regencyId) {
                    $('#district').html('<option value="">Pilih Kecamatan</option>').prop('disabled', false);
                    $('#village').html('<option value="">Pilih Desa/Kelurahan</option>').prop('disabled', false);
                    return;
                }

                $('#district').prop('disabled', true).html('<option value="">Loading...</option>');

                $.ajax({
                    url: '{{ route($districtsRoute, ["regency_id" => ":regency_id"]) }}'.replace(':regency_id', regencyId),
                    type: 'GET',
                    dataType: 'json',
                    timeout: 10000,
                    success: function(data) {
                        let options = '<option value="">Pilih Kecamatan</option>';
                        if (Array.isArray(data) && data.length > 0) {
                            $.each(data, function(key, value) {
                                const selected = (selectedId && value.id == selectedId) ? 'selected' : '';
                                options += `<option value="${value.id}" ${selected}>${value.name}</option>`;
                            });
                        }
                        $('#district').html(options).prop('disabled', false);
                        
                        // Reset village
                        $('#village').html('<option value="">Pilih Desa/Kelurahan</option>');

                        if (selectedId) {
                            setTimeout(() => $('#district').trigger('change'), 100);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading districts:', error);
                        $('#district').html('<option value="">Error: Gagal memuat data</option>').prop('disabled', false);
                        toastr.error('Gagal memuat data kecamatan. Silakan coba lagi.');
                    }
                });
            }

            function loadVillages(districtId, selectedId = null) {
                if (!districtId) {
                    $('#village').html('<option value="">Pilih Desa/Kelurahan</option>').prop('disabled', false);
                    return;
                }

                $('#village').prop('disabled', true).html('<option value="">Loading...</option>');

                $.ajax({
                    url: '{{ route($villagesRoute, ["district_id" => ":district_id"]) }}'.replace(':district_id', districtId),
                    type: 'GET',
                    dataType: 'json',
                    timeout: 10000,
                    success: function(data) {
                        let options = '<option value="">Pilih Desa/Kelurahan</option>';
                        if (Array.isArray(data) && data.length > 0) {
                            $.each(data, function(key, value) {
                                const selected = (selectedId && value.id == selectedId) ? 'selected' : '';
                                options += `<option value="${value.id}" ${selected}>${value.name}</option>`;
                            });
                        }
                        $('#village').html(options).prop('disabled', false);

                        if (selectedId) {
                            $('#village').val(selectedId);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading villages:', error);
                        $('#village').html('<option value="">Error: Gagal memuat data</option>').prop('disabled', false);
                        toastr.error('Gagal memuat data desa/kelurahan. Silakan coba lagi.');
                    }
                });
            }

            // Event handlers for dropdowns
            $('#province').change(function() {
                const provinceId = $(this).val();
                loadRegencies(provinceId);
            });

            $('#regency').change(function() {
                const regencyId = $(this).val();
                loadDistricts(regencyId);
            });

            $('#district').change(function() {
                const districtId = $(this).val();
                loadVillages(districtId);
            });

            // Initialize data on page load
            @if ($user && $user->province_id)
                const initData = {
                    provinceId: '{{ $user->province_id }}',
                    regencyId: '{{ $user->regency_id ?? "" }}',
                    districtId: '{{ $user->district_id ?? "" }}',
                    villageId: '{{ $user->village_id ?? "" }}'
                };

                if (initData.provinceId && initData.regencyId) {
                    setTimeout(() => {
                        loadRegencies(initData.provinceId, initData.regencyId);
                        
                        if (initData.districtId) {
                            setTimeout(() => {
                                loadDistricts(initData.regencyId, initData.districtId);
                                
                                if (initData.villageId) {
                                    setTimeout(() => {
                                        loadVillages(initData.districtId, initData.villageId);
                                    }, 800);
                                }
                            }, 600);
                        }
                    }, 500);
                }
            @endif
        });
    </script>
@endpush