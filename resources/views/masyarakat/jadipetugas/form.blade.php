@extends('components.navbar')

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
                        <form method="POST" action="{{ route('masyarakat.jadi-petugas.submit') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Persyaratan Section -->
                            <div class="alert alert-light border mb-4">
                                <h5 class="mb-3"><strong>Syarat Pendaftaran:</strong></h5>
                                <ul class="mb-0">
                                    <li>Usia minimal 18 tahun</li>
                                    <li>Berdomisili di area layanan TuNetic</li>
                                    <li>Siap bekerja sesuai jadwal yang ditentukan</li>
                                </ul>
                            </div>

                            <!-- Foto Diri Section -->
                            <div class="mb-4">
                                <h5 class="mb-3">Foto Diri</h5>
                                <div class="alert alert-light border">
                                    <small>
                                        <i class="fas fa-info-circle me-2"></i>
                                        Upload foto profile dengan latar belakang polos, ukuran maksimal 2MB
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
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Nama Lengkap *</label>
                                        <input type="text"
                                            class="form-control @error('nama_lengkap') is-invalid @enderror"
                                            name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                                        @error('nama_lengkap')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">No Telepon *</label>
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
                                        <input type="date"
                                            class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                            name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                                        @error('tanggal_lahir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Provinsi</label>
                                        <select class="form-control @error('provinsi') is-invalid @enderror" name="provinsi"
                                            id="provinsi">
                                            <option value="">Pilih Provinsi</option>
                                            <option value="Jawa Tengah"
                                                {{ old('provinsi') == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah
                                            </option>
                                        </select>
                                        @error('provinsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kabupaten/Kota</label>
                                        <select class="form-control @error('kabupaten') is-invalid @enderror"
                                            name="kabupaten" id="kabupaten">
                                            <option value="">Pilih Kabupaten/Kota</option>
                                            <option value="Kota Semarang"
                                                {{ old('kabupaten') == 'Kota Semarang' ? 'selected' : '' }}>Kota Semarang
                                            </option>
                                        </select>
                                        @error('kabupaten')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Kecamatan</label>
                                        <select class="form-control @error('kecamatan') is-invalid @enderror"
                                            name="kecamatan" id="kecamatan">
                                            <option value="">Pilih Kecamatan</option>
                                            <option value="Tembalang"
                                                {{ old('kecamatan') == 'Tembalang' ? 'selected' : '' }}>Tembalang</option>
                                        </select>
                                        @error('kecamatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Desa/Kelurahan</label>
                                        <select class="form-control @error('desa') is-invalid @enderror" name="desa"
                                            id="desa">
                                            <option value="">Pilih Desa/Kelurahan</option>
                                            <option value="Bulusan" {{ old('desa') == 'Bulusan' ? 'selected' : '' }}>
                                                Bulusan</option>
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
                                <div class="alert alert-light border">
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
                                <h5 class="mb-3">Alasan Bergabung</h5>
                                <textarea class="form-control @error('alasan_bergabung') is-invalid @enderror" name="alasan_bergabung"
                                    rows="4" placeholder="Berikan alasan terbaik Anda" required>{{ old('alasan_bergabung') }}</textarea>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Dynamic region selection can be implemented here
            // Example for provinsi change:
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
                // Add more conditions for other provinces
            });

            // Similar logic for kabupaten -> kecamatan -> desa
        });
    </script>
@endsection
