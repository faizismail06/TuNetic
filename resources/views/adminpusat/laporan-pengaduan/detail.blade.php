@extends('layouts.app')

@section('title', 'Detail Laporan Pengaduan')

@section('content')
<div class="container-fluid my-4">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card shadow rounded">
                <div class="card-body">

                    <h3 class="text-center mb-4 font-weight-bold">DETAIL LAPORAN PENGADUAN</h3>

                    <h4 class="mb-2">{{ $laporan->judul }}</h4>

                    <p class="text-muted mb-1">
                        <i class="fas fa-calendar-alt"></i>
                        {{ \Carbon\Carbon::parse($laporan->created_at)->format('d F Y') }}
                        &nbsp;|&nbsp;

                        {{-- Status badge --}}
                        @if ($laporan->status === 2)
                            <span class="badge bg-success">Selesai</span>
                        @elseif ($laporan->status === 1)
                            <span class="badge bg-warning text-dark">Diproses</span>
                        @elseif ($laporan->status === 0)
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-secondary">Tidak Diketahui</span>
                        @endif

                        &nbsp;|&nbsp;
                        <i class="fas fa-user"></i>
                        {{ $laporan->user->name ?? 'Anonim' }}
                    </p>

                    {{-- Kategori --}}
                    <p class="mb-1"><strong>Kategori:</strong> {{ $laporan->kategori }}</p>

                    {{-- Petugas jika ada --}}
                    @if ($laporan->petugas)
                        <p class="mb-1"><strong>Petugas:</strong> {{ $laporan->petugas->nama }}</p>
                    @endif

                    <p class="mt-3">{{ $laporan->deskripsi }}</p>

                    {{-- Lokasi --}}
                    @if ($laporan->latitude && $laporan->longitude)
                        <p class="mb-2">
                            <i class="fas fa-map-marker-alt text-danger"></i>
                            <a href="https://www.google.com/maps?q={{ $laporan->latitude }},{{ $laporan->longitude }}"
                               target="_blank" class="text-decoration-none">
                                {{ $laporan->lokasi ?? 'Lihat Lokasi' }}
                            </a>
                        </p>
                    @endif

                    {{-- Gambar --}}
                    <div class="my-4 text-center">
                        @if ($laporan->gambar)
                            <img src="{{ asset($laporan->gambar) }}"
                                 alt="Foto Laporan"
                                 class="img-fluid rounded shadow"
                                 style="max-height: 300px;">
                        @else
                            <p class="text-muted fst-italic">Tidak ada foto tersedia</p>
                        @endif
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="row mt-5">
                        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">

                            {{-- Kirim ke penugasan --}}
                            @if ($laporan->status == 1)
                                <div class="mb-2">
                                    <form method="POST" action="{{ route('laporan.update', $laporan->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="2">
                                        <button type="submit" class="btn btn-success px-4 py-2">
                                            Tandai Selesai
                                        </button>
                                    </form>
                                </div>
                            @elseif ($laporan->status == 0)
                                <div class="mb-2">
                                    <p class="text-danger fw-bold">Laporan ini sudah ditolak</p>
                                </div>
                            @else
                                <div class="mb-2">
                                    <form method="POST" action="{{ route('laporan.tugaskan', $laporan->id) }}">
                                        @csrf
                                        <div class="input-group">
                                            <select name="id_petugas" class="form-select">
                                                @foreach ($petugas as $p)
                                                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-primary">Tugaskan</button>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            {{-- Tombol Tolak --}}
                            @if ($laporan->status != 0 && $laporan->status != 2)
                                <div class="mb-2">
                                    <form method="POST" action="{{ route('laporan.update', $laporan->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="0">
                                        <input type="text" name="alasan_ditolak" placeholder="Alasan ditolak" required class="form-control mb-2">
                                        <button type="submit" class="btn btn-danger px-4 py-2">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
