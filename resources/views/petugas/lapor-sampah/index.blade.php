@extends('components.navbar')

@section('title', 'Lapor Sampah')

@section('content')
<!-- Tambahkan Bootstrap JS di bagian head atau sebelum </body> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<section id="lapor" class="report-section" style="margin-top: 40px; margin-bottom: 100px;">
    <div class="container">
        <h2 class="section-title mb-2" style="font-family: 'Red Hat Text', sans-serif; font-weight: 700;">Lapor Sampah</h2>
        <p class="mb-5" style="font-family: 'Red Hat Text', sans-serif;">Lihat daftar laporan sampah yang perlu diambil dan segera ditindaklanjuti.</p>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($laporSampah->count() > 0)
            @foreach($laporSampah as $laporan)
                <div class="report-card mb-4 p-4 rounded shadow-sm border bg-white">
                    <div class="row">
                        <div class="col-md-3 col-12 mb-3 mb-md-0">
                            @if($laporan->gambar)
                                <img src="{{ asset('storage/' . $laporan->gambar) }}" alt="Foto Sampah"
                                    class="img-fluid rounded" style="height: 150px; width: 100%; object-fit: cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light rounded"
                                    style="height: 150px; width: 100%;">
                                    <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9 col-12">
                            <h5 class="fw-bold mb-3" style="font-family: 'Red Hat Text', sans-serif;">{{ $laporan->judul }}</h5>

                            <div class="mb-2">
                                <span class="badge rounded-pill px-3 py-2" style="background-color: #e8f5e8; color: #299e63; font-size: 0.9rem;">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $laporan->created_at ? $laporan->created_at->format('d M Y') : 'Tanggal tidak tersedia' }}
                                </span>
                            </div>

                            <div class="mb-2">
                                <span style="color: #299e63; font-weight: 500;">
                                    <i class="fas fa-map-marker-alt me-2"></i> {{ $laporan->alamat }}
                                </span>
                            </div>

                            <div class="mb-3">
                                <span style="color: #666; font-size: 0.95rem;">
                                    <i class="fas fa-comment-dots me-2" style="color: #299e63;"></i>
                                    {{ $laporan->deskripsi }}
                                </span>
                            </div>

                            <div class="mb-3">
                                @if($laporan->status == 1)
                                    <span style="color: #299e63; font-weight: 500;">
                                        <i class="fas fa-check-circle me-2"></i> Sudah diangkut
                                    </span>
                                    @if($laporan->bukti_foto)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $laporan->bukti_foto) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-eye me-1"></i> Lihat Bukti
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <span style="color: #dc3545; font-weight: 500;">
                                        <i class="fas fa-exclamation-circle me-2"></i> Belum diangkut
                                    </span>
                                @endif
                            </div>

                            @if($laporan->status == 0)
                            <button type="button" class="btn px-4 py-2" style="background-color: #ffb800; color: #fff; border: none; font-weight: 500; border-radius: 8px;"
                                    data-bs-toggle="modal" data-bs-target="#buktiModal{{ $laporan->id }}">
                                Kirim Bukti
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Modal untuk Kirim Bukti -->
                <div class="modal fade" id="buktiModal{{ $laporan->id }}" tabindex="-1" aria-labelledby="buktiModalLabel{{ $laporan->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="buktiModalLabel{{ $laporan->id }}">Kirim Bukti - {{ $laporan->judul }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="buktiForm{{ $laporan->id }}" action="{{ route('petugas.lapor.submit-bukti', $laporan->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="fileInput{{ $laporan->id }}" class="form-label">Pilih Foto Bukti:</label>
                                        <input type="file"
                                               class="form-control"
                                               name="bukti_foto"
                                               id="fileInput{{ $laporan->id }}"
                                               accept="image/*"
                                               required
                                               onchange="handleFileSelect(this, {{ $laporan->id }})">
                                        <div class="mt-2">
                                            <small class="text-muted">Format: JPG, PNG, GIF (Max: 2MB)</small>
                                        </div>
                                    </div>

                                    <div id="previewArea{{ $laporan->id }}" style="display: none;" class="mb-3">
                                        <img id="imagePreview{{ $laporan->id }}" style="max-width: 100%; max-height: 200px;" class="img-thumbnail">
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-danger" onclick="clearFile({{ $laporan->id }})">Hapus</button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="keterangan{{ $laporan->id }}" class="form-label">Keterangan:</label>
                                        <textarea class="form-control" name="keterangan_bukti" id="keterangan{{ $laporan->id }}" rows="3" required placeholder="Masukkan keterangan bukti pengangkutan..."></textarea>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Bukti</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-center">
                {{ $laporSampah->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox text-muted mb-3" style="font-size: 4rem;"></i>
                <h5 class="text-muted">Belum ada laporan sampah</h5>
                {{-- <p class="text-muted">Silakan tambah laporan sampah baru</p>
                <a href="{{ route('petugas.lapor.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Tambah Laporan
                </a> --}}
            </div>
        @endif
    </div>
</section>

<script>
// Fungsi untuk menangani preview gambar
function handleFileSelect(input, laporanId) {
    const file = input.files[0];
    if (!file) return;

    // Validasi ukuran (2MB)
    if (file.size > 2 * 1024 * 1024) {
        alert('File terlalu besar! Maksimal 2MB');
        input.value = '';
        return;
    }

    // Validasi tipe
    if (!file.type.startsWith('image/')) {
        alert('File harus berupa gambar!');
        input.value = '';
        return;
    }

    // Show preview
    const previewArea = document.getElementById('previewArea' + laporanId);
    const imagePreview = document.getElementById('imagePreview' + laporanId);

    if (previewArea && imagePreview) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            previewArea.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

// Fungsi untuk menghapus file yang dipilih
function clearFile(laporanId) {
    const fileInput = document.getElementById('fileInput' + laporanId);
    const previewArea = document.getElementById('previewArea' + laporanId);

    if (fileInput) fileInput.value = '';
    if (previewArea) previewArea.style.display = 'none';
}

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert && alert.classList.contains('show')) {
                alert.classList.remove('show');
                setTimeout(function() {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 150);
            }
        }, 5000);
    });
});

// Inisialisasi tooltip jika diperlukan
if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}
</script>
@endsection
