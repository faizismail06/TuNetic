@extends('components.navbar')

@section('content')
<style>
    .btn-custom-success {
        background-color: #299E63 !important;
        border-color: #299E63 !important;
        color: #fff !important;
    }

    .btn-custom-success:hover {
        background-color: #299E63 !important;
        border-color: #299E63 !important;
        color: #fff !important;
    }
</style>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #299E63;">
                    <h5 class="mb-0">Ubah Password</h5>
                </div>

                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif


                    <form method="POST" action="{{ route('masyarakat.akun.password.update') }}">
                        @csrf
                        @method('PUT')

                        {{-- Password Baru --}}
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru *</label>
                            <input
                                type="password"
                                class="form-control @error('new_password') is-invalid @enderror"
                                id="new_password"
                                name="new_password"
                                placeholder="Masukkan password baru"
                                required
                            >
                            <div class="form-text">
                                Gunakan minimal 8 karakter dengan kombinasi huruf dan angka.
                            </div>
                            @error('new_password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="mb-4">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru *</label>
                            <input
                                type="password"
                                class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                id="new_password_confirmation"
                                name="new_password_confirmation"
                                placeholder="Konfirmasi password"
                                required
                            >
                            @error('new_password_confirmation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-custom-success text-white">
                                <i class="fas fa-save me-2"></i> Simpan Password
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
    // Validasi real-time
    document.getElementById('new_password').addEventListener('input', function() {
        const password = this.value;
        const isValid = password.length >= 8 && /[a-zA-Z]/.test(password) && /[0-9]/.test(password);

        if (isValid) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else {
            this.classList.remove('is-valid');
        }
    });
</script>
@endsection