@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">DASHBOARD</h1>
                <p>Pengelolaan Sampah di Kecamatan Tembalang</p>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="row">

            {{-- Ringkasan Data --}}
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Ringkasan Data</h5>
                        <a href="#" class="btn btn-sm btn-success ml-auto">Lihat Laporan</a>
                    </div>
                    <div class="card-body">
                        <canvas id="ringkasanChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Presentase Pengangkutan --}}
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Presentase Pengangkutan Sampah Armada Mingguan</h5>
                        <a href="#" class="btn btn-sm btn-success ml-auto">Lihat Laporan</a>
                    </div>
                    <div class="card-body">
                        <canvas id="pengangkutanChart"></canvas>
                        <div class="legend mt-3">
                            @foreach($chartData as $index => $tps)
                                <span class="legend-item mr-3">
                                    <i class="fas fa-circle" style="color: {{ ['#007bff', '#20c997', '#28a745', '#dc3545', '#ffc107'][$index % 5] }}"></i>
                                    {{ $tps['label'] }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kategori Masalah --}}
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Laporan Warga Berdasarkan Kategori Masalah</h5>
                        <a href="#" class="btn m-2 btn-sm btn-success ml-auto">Lihat Laporan</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <canvas id="kategoriMasalahChart"></canvas>
                            </div>
                            <div class="col-md-6">
                                @foreach($kategoriMasalah as $index => $kategori)
                                    <div class="kategori-item d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="dot mr-2" style="background-color: {{ ['#28a745', '#ffc107', '#8B4513', '#f8f9fa', '#343a40'][$index % 5] }};"></span>
                                            <span>{{ $kategori->nama }}</span>
                                        </div>
                                        <span>{{ $kategori->persentase }}%</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Daftar TPS --}}
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="m-0">Daftar Lokasi TPS Di Tembalang</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                @foreach($tpsKolom1 as $tps)
                                    <div class="tps-item mb-2"><i class="fas fa-trash-alt text-success"></i> {{ $tps->nama_lokasi }}</div>
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                @foreach($tpsKolom2 as $tps)
                                    <div class="tps-item mb-2"><i class="fas fa-trash-alt text-success"></i> {{ $tps->nama_lokasi }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endpush


@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    window.ringkasanData = [{{ $countWarga }}, {{ $countPetugas }}, {{ $countArmada }}, {{ $countLaporan }}];
    window.kendaraanLabels = {!! json_encode($nomorKendaraan) !!};
    window.pengangkutanDatasets = {!! json_encode($chartData) !!};
    window.kategoriLabels = {!! json_encode($kategoriMasalah->pluck('nama')) !!};
    window.kategoriData = {!! json_encode($kategoriMasalah->pluck('persentase')) !!};
</script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endpush
