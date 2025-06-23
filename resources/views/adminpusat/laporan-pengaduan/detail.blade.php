@extends('layouts.app')

@section('title', 'Detail Laporan Pengaduan')

@section('content')
    <div class="container-fluid my-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
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

                            @php
                                $statusBadge = [
                                    0 => ['text' => 'Belum Diangkut', 'class' => 'secondary'],
                                    1 => ['text' => 'Sedang Proses', 'class' => 'warning text-dark'],
                                    2 => ['text' => 'Ditolak', 'class' => 'danger'],
                                    3 => ['text' => 'Sudah Diangkut', 'class' => 'success'],
                                ];
                                $badge = $statusBadge[$laporan->status] ?? [
                                    'text' => 'Tidak Diketahui',
                                    'class' => 'default text-dark',
                                ];
                            @endphp
                            <span class="badge bg-{{ $badge['class'] }}">{{ $badge['text'] }}</span>
                            &nbsp;|&nbsp;
                            <i class="fas fa-user"></i>
                            {{ $laporan->user->name ?? 'Anonim' }}
                        </p>

                        <p class="mb-1"><strong>Kategori:</strong> {{ $laporan->kategori }}</p>

                        @if ($laporan->petugas)
                            <p class="mb-1"><strong>Petugas:</strong> {{ $laporan->petugas->name }}</p>
                        @endif

                        @if ($laporan->status == 2 && $laporan->alasan_ditolak)
                            <p class="mb-1 text-danger"><strong>Alasan Ditolak:</strong> {{ $laporan->alasan_ditolak }}</p>
                        @endif

                        <p class="mt-3">{{ $laporan->deskripsi }}</p>

                        @if ($laporan->latitude && $laporan->longitude)
                            <p class="mb-2">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                <a href="https://www.google.com/maps?q={{ $laporan->latitude }},{{ $laporan->longitude }}"
                                    target="_blank" class="text-decoration-none">
                                    {{ $laporan->lokasi ?? 'Lihat Lokasi' }}
                                </a>
                            </p>
                        @endif

                        <div class="my-4 text-center">
                            @if ($laporan->gambar)
                                <!-- <img src="{{ asset( $laporan->gambar) }}" alt="Foto Laporan" -->
                                <img src="{{ asset('storage/laporan_warga/' . $laporan->gambar) }}" alt="Foto Laporan"
                                    class="img-fluid rounded shadow" style="max-height: 300px;">
                            @else
                                <p class="text-muted fst-italic">Tidak ada foto tersedia</p>
                            @endif
                        </div>

                        <div class="row mt-5">
                            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">

                                @if ($laporan->status === 0)
                                    <div class="mb-2">
                                        <form method="POST" action="{{ route('laporan.tugaskan', $laporan->id) }}">
                                            @csrf
                                            <div class="input-group">
                                                <select name="id_petugas" class="form-select">
                                                    @foreach ($petugas as $p)
                                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-primary">Tugaskan</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="mb-2">
                                        <form method="POST" action="{{ route('laporan.update', $laporan->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="2">
                                            <input type="text" name="alasan_ditolak" placeholder="Alasan ditolak"
                                                required class="form-control mb-2">
                                            <input type="hidden" name="kategori" value="{{ $laporan->kategori }}">
                                            <button type="submit" class="btn btn-danger px-4 py-2">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                @elseif ($laporan->status === 2)
                                    <div class="mb-2">
                                        <p class="text-danger fw-bold">Laporan ini telah ditolak.</p>
                                    </div>
                                @elseif ($laporan->status === 3)
                                    <div class="mb-2">
                                        <p class="text-success fw-bold">Laporan ini telah selesai diangkut.</p>
                                    </div>
                                @elseif ($laporan->status === 1)
                                    <div class="mb-2">
                                        <p class="text-warning">Menunggu petugas menyelesaikan tugas.</p>
                                    </div>
                                @endif

                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
