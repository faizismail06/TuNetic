@extends('components.navbar')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-lg">
                <div class="card-header text-white" style="background-color: #299E63">
                    <h4 class="mb-0">
                        <i class="fas fa-user-shield" style="font-size: 1.4rem; margin-right: 10px;"></i> Registrasi Petugas Kebersihan
                    </h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('masyarakat.jadi-petugas.submit') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Persyaratan Section with Checkboxes -->
                        <div class="alert alert-light border mb-4">
                            <h5 class="mb-3"><strong>Sebelum Mendaftar, Pastikan Anda :</strong></h5>
                            <div class="form-check mb-3">
                                <input class="form-check-input requirement-check" type="checkbox" id="req1" required>
                                <label class="form-check-label" for="req1">
                                    Usia minimal 18 tahun
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input requirement-check" type="checkbox" id="req2" required>
                                <label class="form-check-label" for="req2">
                                    Berdomisili di area layanan TuNetic
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input requirement-check" type="checkbox" id="req3" required>
                                <label class="form-check-label" for="req3">
                                    Siap bekerja sesuai jadwal yang ditentukan
                                </label>
                            </div>
                        </div>

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
                                    <input type="file" id="fotoDiriUpload" class="d-none" name="foto_diri" accept="image/*" required>
                                </div>
                                <p class="small text-muted mb-0">
                                    Gambar Profile Anda sebaiknya memiliki rasio 1:1 dan berukuran tidak lebih dari 2MB.
                                </p>
                            </div>
                            <div id="imagePreview" class="text-center mt-3 d-none">
                                <img id="previewImage" src="#" alt="Preview" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger mt-2" id="removeImage">
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
                                <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                       name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Masukkan Nama Lengkap" required>
                                @error('nama_lengkap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Username *</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       name="username" value="{{ old('username') }}" placeholder="Masukkan Username" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">No Telepon *</label>
                                <input type="tel" class="form-control @error('no_telepon') is-invalid @enderror" 
                                       name="no_telepon" value="{{ old('no_telepon') }}" placeholder="Masukkan No Telepon" required>
                                @error('no_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email *') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" placeholder="Masukkan Email Aktif">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
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
                                    <label class="form-label">Provinsi</label>
                                    <select class="form-control @error('provinsi') is-invalid @enderror" name="provinsi" id="provinsi">
                                        <option value="">Pilih Provinsi</option>
                                        <option value="Jawa Tengah" {{ old('provinsi') == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                                    </select>
                                    @error('provinsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kabupaten/Kota</label>
                                    <select class="form-control @error('kabupaten') is-invalid @enderror" name="kabupaten" id="kabupaten">
                                        <option value="">Pilih Kabupaten/Kota</option>
                                        <option value="Kota Semarang" {{ old('kabupaten') == 'Kota Semarang' ? 'selected' : '' }}>Kota Semarang</option>
                                    </select>
                                    @error('kabupaten')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Section Kecamatan dan Desa/Kelurahan - sekarang sejajar -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kecamatan</label>
                                    <select class="form-control @error('kecamatan') is-invalid @enderror" name="kecamatan" id="kecamatan">
                                        <option value="">Pilih Kecamatan</option>
                                        <option value="Tembalang" {{ old('kecamatan') == 'Tembalang' ? 'selected' : '' }}>Tembalang</option>
                                    </select>
                                    @error('kecamatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Desa/Kelurahan</label>
                                    <select class="form-control @error('desa') is-invalid @enderror" name="desa" id="desa">
                                        <option value="">Pilih Desa/Kelurahan</option>
                                        <option value="Bulusan" {{ old('desa') == 'Bulusan' ? 'selected' : '' }}>Bulusan</option>
                                    </select>
                                    @error('desa')
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
                                <input type="file" id="simUpload" class="d-none" name="foto_sim" accept="image/jpeg,image/jpg,image/png">
                            </div>
                            <p class="small text-muted mb-0">
                                Unggah bukti berupa foto SIM dalam format JPG/JPEG/PNG (maksimal 2 MB)
                            </p>
                        </div>
                        <div id="simPreview" class="text-center mt-3 d-none">
                            <img id="previewSim" src="#" alt="Preview SIM" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: contain;">
                            <button type="button" class="btn btn-sm btn-danger mt-2" id="removeSim">
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
                            <textarea class="form-control @error('alasan_bergabung') is-invalid @enderror" 
                                      name="alasan_bergabung" rows="4" placeholder="Berikan alasan terbaik Anda" required>{{ old('alasan_bergabung') }}</textarea>
                            @error('alasan_bergabung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-lg text-white" style="background-color: #299E63">
                                Kirim Pendaftaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Image preview functionality
    document.getElementById('fotoDiriUpload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('previewImage');
        const previewContainer = document.getElementById('imagePreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.src = event.target.result;
                previewContainer.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove image functionality
    document.getElementById('removeImage').addEventListener('click', function() {
        document.getElementById('fotoDiriUpload').value = '';
        document.getElementById('imagePreview').classList.add('d-none');
    });
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
</style>
@endsection

@section('scripts')
<script>
    // Image preview functionality for foto diri
    document.getElementById('fotoDiriUpload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('previewImage');
        const previewContainer = document.getElementById('imagePreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.src = event.target.result;
                previewContainer.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove image functionality for foto diri
    document.getElementById('removeImage').addEventListener('click', function() {
        document.getElementById('fotoDiriUpload').value = '';
        document.getElementById('imagePreview').classList.add('d-none');
    });

    // Image preview functionality for SIM
    document.getElementById('simUpload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('previewSim');
        const previewContainer = document.getElementById('simPreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.src = event.target.result;
                previewContainer.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove image functionality for SIM
    document.getElementById('removeSim').addEventListener('click', function() {
        document.getElementById('simUpload').value = '';
        document.getElementById('simPreview').classList.add('d-none');
    });

    // Dynamic region selection
    document.getElementById('provinsi').addEventListener('change', function() {
        const provinsi = this.value;
        const kabupatenSelect = document.getElementById('kabupaten');
        
        // Clear existing options
        kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
        
        if (provinsi === 'Jawa Tengah') {
            // Add options for Jawa Tengah
            const options = ['Kota Semarang', 'Kabupaten Semarang', 'Kota Salatiga'];
            options.forEach(opt => {
                const option = document.createElement('option');
                option.value = opt;
                option.textContent = opt;
                kabupatenSelect.appendChild(option);
            });
        }
    });
</script>
@endsection

