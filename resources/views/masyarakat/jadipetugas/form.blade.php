@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header text-white" style="background-color: #299E63">
                    <h4 class="mb-0">
                        <i class="fas fa-user-shield me-2"></i> Registrasi Petugas Kebersihan
                    </h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('masyarakat.jadipetugas.submit') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Foto Diri Section -->
                        <div class="mb-4">
                            <h5><i class="fas fa-camera me-2"></i> Foto Diri</h5>
                            <div class="alert alert-info">
                                <small>
                                    <i class="fas fa-info-circle me-2"></i> 
                                    Upload foto diri dengan latar belakang polos, ukuran maksimal 2MB
                                </small>
                            </div>
                            <input type="file" class="form-control @error('foto_diri') is-invalid @enderror" 
                                   name="foto_diri" accept="image/*" required>
                            @error('foto_diri')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Personal Data Section -->
                        <div class="mb-4">
                            <h5><i class="fas fa-user-circle me-2"></i> Data Pribadi</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap *</label>
                                    <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                           name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                                    @error('nama_lengkap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Username *</label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                           name="username" value="{{ old('username') }}" required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">No Telepon *</label>
                                    <input type="tel" class="form-control @error('no_telepon') is-invalid @enderror" 
                                           name="no_telepon" value="{{ old('no_telepon') }}" required>
                                    @error('no_telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Birth Date and Address Section -->
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                           name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Provinsi</label>
                                    <select class="form-control @error('provinsi') is-invalid @enderror" name="provinsi" id="provinsi">
                                        <option value="">Pilih Provinsi</option>
                                        <option value="Jawa Tengah" {{ old('provinsi') == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                                        <!-- Add more provinces as needed -->
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
                            <h5><i class="fas fa-id-card me-2"></i> Upload SIM</h5>
                            <div class="alert alert-info">
                                <small>
                                    <i class="fas fa-info-circle me-2"></i> 
                                    Upload foto SIM dalam format JPG/JPEG/PNG (maksimal 2MB)
                                </small>
                            </div>
                            <input type="file" class="form-control @error('foto_sim') is-invalid @enderror" 
                                   name="foto_sim" accept="image/*">
                            @error('foto_sim')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Reason Section -->
                        <div class="mb-4">
                            <h5><i class="fas fa-comment me-2"></i> Alasan Bergabung</h5>
                            <textarea class="form-control @error('alasan_bergabung') is-invalid @enderror" 
                                      name="alasan_bergabung" rows="4" required>{{ old('alasan_bergabung') }}</textarea>
                            @error('alasan_bergabung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-lg text-white" style="background-color: #299E63">
                                <i class="fas fa-paper-plane me-2"></i> Kirim Pendaftaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
    .card {
        border-radius: 15px;
    }
    .card-header {
        border-radius: 15px 15px 0 0 !important;
    }
    textarea {
        resize: none;
    }
    .form-control:focus {
        border-color: #299E63;
        box-shadow: 0 0 0 0.25rem rgba(41, 158, 99, 0.25);
    }
</style>
@endsection

@section('scripts')
<script>
    // Dynamic dropdown for regions (you can implement AJAX calls here)
    document.addEventListener('DOMContentLoaded', function() {
        // You can add JavaScript for dynamic region selection here
        // Example:
        document.getElementById('provinsi').addEventListener('change', function() {
            // AJAX call to get kabupaten based on selected provinsi
        });
    });
</script>
@endsection