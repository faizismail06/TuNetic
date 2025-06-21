@extends('layouts.landing')

@section('title', 'Edukasi - Semua Artikel')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12 text-center fade-in-up">
                <h2 class="fw-semibold mb-3" style="font-family: 'Red Hat Text', sans-serif;">Semua Artikel Edukasi</h2>
            </div>
        </div>

        <div class="row">
            @forelse($articles as $key => $artikel)
                {{-- Ini adalah baris yang sudah diperbaiki, tanpa teks tambahan di akhir --}}
                <div class="col-md-6 mb-4 fade-in-up" style="animation-delay: {{ $key * 0.1 }}sw">
                    <div class="card border-0 shadow-sm h-100 article-card">
                        <div class="row g-0">
                            <div class="col-md-5">
                                <img src="{{ asset('storage/' . $artikel->gambar) }}" class="img-fluid rounded-start h-100" alt="{{ $artikel->judul_artikel }}" style="object-fit: cover;">
                            </div>
                            <div class="col-md-7 d-flex flex-column">
                                <div class="card-body">
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($artikel->tanggal_publikasi)->locale('id')->isoFormat('D MMMM Y') }}</small>
                                    <h5 class="card-title mt-2" style="font-weight: 600;">{{ Str::limit($artikel->judul_artikel, 60) }}</h5>
                                    <p class="card-text" style="font-weight: 300;">{{ Str::limit($artikel->deskripsi_singkat, 100) }}</p>
                                </div>
                                <div class="card-footer bg-white border-0 mt-auto">
                                    <a href="{{ $artikel->link_artikel }}" target="_blank" class="btn btn-sm btn-outline-success">
                                        Baca Selengkapnya <i class="fas fa-external-link-alt ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 fade-in-up" style="animation-delay: 0.2s;">
                    <div class="text-center py-5 px-4 empty-state-container">
                        <div class="empty-state-icon mb-4">
                            <i class="fas fa-seedling fa-5x"></i>
                        </div>
                        <h2 class="empty-state-title">Wawasan Baru Sedang Dipersiapkan!</h2>
                        <p class="empty-state-text">
                            Tim kami sedang bekerja keras menyiapkan artikel-artikel menarik seputar lingkungan dan pengelolaan sampah untuk Anda. Silakan kembali lagi nanti!
                        </p>
                        <a href="{{ route('home') }}" class="btn btn-success mt-3">
                            <i class="fas fa-home me-2"></i>Kembali ke Beranda
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($articles->hasPages())
            <div class="d-flex justify-content-center mt-4 fade-in-up" style="animation-delay: 0.5s;">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
</section>
@endsection