@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header text-white" style="background-color: #299E63">
                    <h4 class="mb-0">
                        <i class="fas fa-user-shield me-2"></i> Bergabung Sebagai Petugas Kebersihan
                    </h4>
                </div>

                <div class="card-body">
                    {{-- Persyaratan --}}
                    <div class="alert alert-info">
                        <h5 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i> Sebelum Mendaftar, Pastikan Anda:
                        </h5>
                        <ul class="mb-0">
                            <li><i class="fas fa-check me-2" style="color: #299E63"></i> Berusia minimal 18 tahun</li>
                            <li><i class="fas fa-check me-2" style="color: #299E63"></i> Berdomisili di area layanan TuNetic</li>
                            <li><i class="fas fa-check me-2" style="color: #299E63"></i> Siap bekerja sesuai aturan kebersihan</li>
                        </ul>
                    </div>

                    {{-- Status Pendaftaran --}}
                    <div class="alert {{ auth()->user()->is_petugas ? 'alert-warning' : 'alert-primary' }} mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas {{ auth()->user()->is_petugas ? 'fa-user-check' : 'fa-user-clock' }} fa-2x me-3" style="color: #299E63"></i>
                            <div>
                                <h5 class="mb-1">
                                    @if(auth()->user()->is_petugas)
                                        Anda sudah terdaftar sebagai petugas kebersihan.
                                    @else
                                        Anda belum terdaftar sebagai petugas kebersihan.
                                    @endif
                                </h5>
                                @if(auth()->user()->petugasRequest)
                                    <small class="d-block">
                                        Status pengajuan: 
                                        <span class="badge" style="background-color: #299E63">
                                            {{ auth()->user()->petugasRequest->status_label }}
                                        </span>
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Formulir Pendaftaran --}}
                    @unless(auth()->user()->is_petugas)
                        <form method="POST" action="{{ route('user.petugas.submit') }}" enctype="multipart/form-data">
                            @csrf

                            <h5 class="mb-3 text-center">
                                <i class="fas fa-edit me-2" style="color: #299E63"></i> Daftar Sekarang
                            </h5>

                            {{-- Data Pribadi --}}
                            <div class="mb-3">
                                <label class="form-label">Alamat Lengkap *</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                    name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Upload Dokumen --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Foto KTP *</label>
                                    <input type="file" class="form-control @error('ktp') is-invalid @enderror" 
                                        name="ktp" accept="image/*" required>
                                    @error('ktp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Format: JPG/PNG, maks 2MB</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Surat Keterangan Sehat (Opsional)</label>
                                    <input type="file" class="form-control @error('sertifikat') is-invalid @enderror" 
                                        name="sertifikat" accept=".pdf,.jpg,.png">
                                    @error('sertifikat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Format: PDF/JPG/PNG, maks 5MB</small>
                                </div>
                            </div>

                            {{-- Pengalaman --}}
                            <div class="mb-4">
                                <label class="form-label">Pengalaman Kerja (Jika Ada)</label>
                                <textarea class="form-control @error('pengalaman') is-invalid @enderror" 
                                    name="pengalaman" rows="3">{{ old('pengalaman') }}</textarea>
                                @error('pengalaman')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tombol Submit --}}
                            <div class="d-grid">
                                <button type="submit" class="btn btn-lg" style="background-color: #299E63; color: white">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Permohonan
                                </button>
                            </div>
                        </form>
                    @endunless
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
    /* Warna hijau untuk semua elemen yang membutuhkan */
    .bg-success-custom {
        background-color: #299E63 !important;
    }
    .text-success-custom {
        color: #299E63 !important;
    }
    .btn-success-custom {
        background-color: #299E63;
        color: white;
    }
    .btn-success-custom:hover {
        background-color: #218753;
    }
</style>
@endsection