@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                {{-- Judul Dashboard --}}
                <h1 class="m-0">DASHBOARD</h1>
                <p>Perhitungan Sampah TPST</p>
            </div>
            {{-- Anda bisa menambahkan info user di sini jika tidak ada di layout utama --}}
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            {{-- Grafik Timbulan Sampah --}}
            <div class="col-12"> {{-- Grafik dibuat memenuhi lebar --}}
                <form method="GET" class="mb-3">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-3">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-3 align-self-end">
                            <button class="btn btn-success" type="submit">Filter</button>
                            <a href="{{ route('admin_tpst.admintpst.dashboard.index') }}" class="btn btn-warning text-white">Reset</a>
                        </div>
                    </div>
                </form>
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Persentase Timbulan Sampah (Kilogram)</h5>
                        {{-- Tombol Lihat Laporan dibuat lebih mirip gambar --}}
                        <a href="#" class="btn btn-sm btn-outline-success ml-auto">Lihat Laporan</a>
                    </div>
                    <div class="card-body">
                        {{-- Legenda Grafik (Dibuat Manual Sesuai Gambar) --}}
                        <div class="chart-legend text-center mb-4">
                            <span class="legend-item mr-3"><i class="fas fa-circle" style="color: #4285F4;"></i> TPST 1</span>
                            <span class="legend-item mr-3"><i class="fas fa-circle" style="color: #34A853;"></i> TPST 2</span>
                            <span class="legend-item mr-3"><i class="fas fa-circle" style="color: #0F9D58;"></i> TPST 3</span>
                            <span class="legend-item mr-3"><i class="fas fa-circle" style="color: #DB4437;"></i> TPST 4</span>
                            <span class="legend-item"><i class="fas fa-circle" style="color: #F4B400;"></i> TPST 5</span>
                        </div>
                        {{-- Area Grafik --}}
                        <canvas id="timbulanSampahChart" style="min-height: 350px; height: 350px; max-height: 350px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>

            {{-- Daftar TPS --}}
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="m-0">Lokasi TPST</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                @foreach($tpsKolom1 as $tps)
                                    <div class="tps-item mb-2"><i class="fas fa-map-marker-alt text-success mr-2"></i> {{ $tps->nama_lokasi }}</div>
                                @endforeach
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                @foreach($tpsKolom2 as $tps)
                                    <div class="tps-item mb-2"><i class="fas fa-map-marker-alt text-success mr-2"></i> {{ $tps->nama_lokasi }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> {{-- End Row --}}
    </div> {{-- End Container-fluid --}}
</div> {{-- End Content --}}
@endsection

@push('css')
{{-- Tambahkan CSS khusus jika perlu --}}
<style>
    .content-header p {
        color: #6c757d; /* Warna abu-abu untuk sub-judul */
        font-size: 0.95em;
    }
    .btn-outline-success {
        color: #28a745;
        border-color: #28a745;
    }
    .btn-outline-success:hover {
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
    }
    .chart-legend .legend-item {
        font-size: 0.9em;
    }
    .chart-legend .fa-circle {
        font-size: 0.8em;
        vertical-align: middle;
        margin-right: 5px;
    }
    .tps-item {
        font-size: 1em;
        padding: 5px 0;
    }
</style>
{{-- Pastikan Font Awesome sudah terload (biasanya ada di layouts.app) --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> --}}
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const timbulanLabels = @json($chartLabels);
    const timbulanData = @json($chartData);
    const chartColors = @json($chartColors);
    const numPerRute = {{ $numPerRute }};
    const armadaDetail = @json($armadaDetails);

    const timbulanColors = [];
    for (let i = 0; i < timbulanLabels.length; i++) {
        timbulanColors.push(chartColors[Math.floor(i / numPerRute)] ?? '#ccc');
    }

    const ctx = document.getElementById('timbulanSampahChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: timbulanLabels,
            datasets: [{
                label: 'Timbulan Sampah (Kg)',
                data: timbulanData,
                backgroundColor: timbulanColors,
                borderColor: timbulanColors,
                borderWidth: 1,
                barPercentage: 0.8,
                categoryPercentage: 0.6
            }]
        },
        options: {
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        afterBody: function(context) {
                            const idx = context[0].dataIndex;
                            const detail = armadaDetail[idx];
                            if (!detail) return '';
                            return [
                                `Armada: ${detail.jenis_kendaraan} ${detail.merk_kendaraan}`,
                                `No Polisi: ${detail.no_polisi}`,
                                `Tanggal: ${detail.tanggal}`
                            ];
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        callback: function(value, index, values) {
                            const labels = this.getLabels();
                            if (index % numPerRute === 0) {
                                return labels[index].split('_')[0];
                            }
                            return '';
                        },
                        autoSkip: false,
                        maxRotation: 0,
                        minRotation: 0
                    }
                },
                y: {
                    beginAtZero: true,
                    max: Math.max(600, Math.max(...timbulanData) + 100),
                    ticks: { stepSize: 100 },
                    grid: { drawBorder: false }
                }
            }
        }
    });
</script>
@endpush