{{-- Memakai 'baju seragam' dari layout utama Anda --}}
@extends('layouts.landing')

{{-- Mengganti judul tab browser --}}
@section('title', 'Edukasi - Semua Artikel')

{{-- Mulai mengisi konten halaman --}}
@section('content')
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <h2 class="fw-semibold mb-3" style="font-family: 'Red Hat Text', sans-serif;">Semua Artikel Edukasi</h2>
            </div>
        </div>

        <div class="row">
            @forelse($articles as $artikel)
                <div class="col-md-6 mb-4">
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
                <div class="col-12 text-center py-5">
                    <p>Belum ada artikel yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $articles->links() }}
        </div>
    </div>
</section>
@endsection