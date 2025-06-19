@extends('layouts.landing')

@section('title', 'Daftar Lokasi TPS')

@section('content')
<section class="py-5" style="min-height: 70vh;">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-12 text-center fade-in-up">
                <h2 class="fw-semibold mb-3" style="font-family: 'Red Hat Text', sans-serif;">Daftar Lokasi TPS</h2>
                <p class="text-muted">Temukan Tempat Penampungan Sementara yang terdaftar di sekitar Anda.</p>
            </div>
        </div>

        <div class="row g-4">
            @forelse($tps as $key => $nama)
                <div class="col-md-4 col-sm-6 fade-in-up" style="animation-delay: {{ $key * 0.05 }}s;">
                    <div class="card border-0 shadow-sm h-100 article-card text-center">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center">
                            <img src="{{ asset('assets/images/sampahtps.png') }}" alt="TPS Icon" class="tps-icon mb-3" style="width: 60px; height: auto;">
                            <h5 class="card-title" style="font-weight: 600;">{{ $nama->nama_lokasi }}</h5>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 fade-in-up" style="animation-delay: 0.2s;">
                    <div class="text-center py-5 px-4 empty-state-container">
                        <div class="empty-state-icon mb-4">
                            <i class="fas fa-map-marker-alt fa-5x"></i>
                        </div>
                        <h2 class="empty-state-title">Lokasi Belum Tersedia</h2>
                        <p class="empty-state-text">
                            Saat ini belum ada data lokasi TPS yang dapat kami tampilkan. Tim kami sedang melakukan pembaruan data. Silakan kembali lagi nanti!
                        </p>
                        <a href="{{ route('home') }}" class="btn btn-success mt-3">
                            <i class="fas fa-home me-2"></i>Kembali ke Beranda
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
