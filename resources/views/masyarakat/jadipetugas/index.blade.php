@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-lg">
                <div class="card-header text-white" style="background-color: #299E63">
                    <h4 class="mb-0">
                        <i class="fas fa-user-shield me-2"></i> Bergabung Sebagai Petugas Kebersihan
                    </h4>
                </div>

                <div class="card-body">
                    {{-- Checklist Persyaratan --}}
                    <div class="alert bg-white text-dark border shadow-sm">
                        <h5 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i> Sebelum Mendaftar, Pastikan Anda:
                        </h5>
                        <div id="checklist-container" class="mt-3">
                            <div class="form-check checklist-item mb-3">
                                <input class="form-check-input" type="checkbox" id="check1">
                                <label class="form-check-label" for="check1">
                                    <i class="fas fa-check-circle me-2 d-none text-success check-icon"></i>
                                    Berusia minimal 18 tahun
                                </label>
                            </div>
                            <div class="form-check checklist-item mb-3">
                                <input class="form-check-input" type="checkbox" id="check2">
                                <label class="form-check-label" for="check2">
                                    <i class="fas fa-check-circle me-2 d-none text-success check-icon"></i>
                                    Berdomisili di area layanan TuNic
                                </label>
                            </div>
                            <div class="form-check checklist-item mb-3">
                                <input class="form-check-input" type="checkbox" id="check3">
                                <label class="form-check-label" for="check3">
                                    <i class="fas fa-check-circle me-2 d-none text-success check-icon"></i>
                                    Siap bekerja sesuai aturan kebersihan
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Status Pendaftaran --}}
                    <div class="alert alert-danger mb-4" id="statusPendaftaran">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle fa-2x me-3 text-danger"></i>
                            <div>
                                <h5 class="mb-1">Anda belum terdaftar sebagai petugas kebersihan.</h5>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Daftar --}}
                    <button id="daftarBtn" class="btn btn-success w-100" disabled>
                        <i class="fas fa-pencil-alt me-2"></i> Daftar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Peringatan --}}
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #299E63">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>Persyaratan Terpenuhi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Selamat! Semua persyaratan telah terpenuhi. Klik "Lanjutkan" untuk mengisi formulir pendaftaran.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="{{ route('user.jadipetugas.form') }}" class="btn text-white" style="background-color: #299E63">
                    Lanjutkan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('#checklist-container input[type="checkbox"]');
        const daftarBtn = document.getElementById('daftarBtn');
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        const statusPendaftaran = document.getElementById('statusPendaftaran');

        function checkAllChecked() {
            let allChecked = true;
            
            checkboxes.forEach(checkbox => {
                const label = checkbox.nextElementSibling;
                const icon = label.querySelector('.check-icon');
                
                if (checkbox.checked) {
                    icon.classList.remove('d-none');
                    label.style.color = '#299E63';
                } else {
                    icon.classList.add('d-none');
                    label.style.color = '';
                    allChecked = false;
                }
            });
            
            daftarBtn.disabled = !allChecked;
            
            if (allChecked) {
                successModal.show();
                statusPendaftaran.classList.add('d-none');
            } else {
                statusPendaftaran.classList.remove('d-none');
            }
        }
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', checkAllChecked);
        });

        daftarBtn.addEventListener('click', function() {
            successModal.show();
        });
    });
</script>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 15px;
    }
    .card-header {
        border-radius: 15px 15px 0 0 !important;
    }
    .checklist-item {
        transition: all 0.3s ease;
    }
    .checklist-item:hover {
        transform: translateX(5px);
    }
    .btn-success {
        background-color: #299E63;
        border-color: #299E63;
    }
    .btn-success:hover {
        background-color: #218753;
        border-color: #218753;
    }
    .check-icon {
        transition: all 0.3s ease;
    }
</style>
@endsection