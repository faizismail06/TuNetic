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
    document.addEventListener("DOMContentLoaded", function() {

        // --- Data Sampah (Contoh - Sesuaikan dengan data dari Controller) ---
        // Data ini mencoba mereplikasi visualisasi pada gambar.
        // Label X-Axis dibuat unik untuk setiap bar agar bisa diberi warna berbeda.
        // Ticks X-Axis akan difilter agar hanya menampilkan label grup (H 1234, dll.)
        const timbulanLabels = ['H 1234_1', 'H 1234_2', 'H 4567_1', 'H 4567_2', 'H 5678_1', 'H 5678_2', 'H 9881_1', 'H 9881_2', 'H 6874_1', 'H 6874_2'];
        const timbulanData = [500, 440, 200, 200, 340, 480, 270, 110, 220, 100];
        const timbulanColors = [
            '#4285F4', '#4285F4', // TPST 1
            '#34A853', '#34A853', // TPST 2
            '#0F9D58', '#0F9D58', // TPST 3
            '#DB4437', '#DB4437', // TPST 4
            '#F4B400', '#F4B400', // TPST 5
        ];

        // --- Inisialisasi Grafik Timbulan Sampah ---
        const ctxTimbulan = document.getElementById('timbulanSampahChart').getContext('2d');
        new Chart(ctxTimbulan, {
            type: 'bar',
            data: {
                labels: timbulanLabels,
                datasets: [{
                    label: 'Timbulan Sampah (Kg)',
                    data: timbulanData,
                    backgroundColor: timbulanColors,
                    borderColor: timbulanColors,
                    borderWidth: 1,
                    barPercentage: 0.8,    // Lebar bar relatif terhadap kategori
                    categoryPercentage: 0.6 // Lebar kategori relatif terhadap area
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Sembunyikan legenda default karena sudah dibuat manual
                    },
                    tooltip: {
                         callbacks: {
                            title: function(tooltipItems) {
                                // Menampilkan label grup (H 1234) sebagai judul tooltip
                                let label = tooltipItems[0].label;
                                return label.split('_')[0];
                            },
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y + ' Kg';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false // Hilangkan grid vertikal
                        },
                        ticks: {
                            // Tampilkan hanya label grup (H xxxx) di tengah-tengah grup bar
                            callback: function(value, index, values) {
                                const labels = this.getLabels();
                                if (index % 2 === 0) { // Hanya tampilkan untuk bar pertama di grup
                                    return labels[index].split('_')[0];
                                }
                                return ''; // Sembunyikan label untuk bar kedua
                            },
                            autoSkip: false, // Jangan lewati label secara otomatis
                            maxRotation: 0,  // Jaga label tetap horizontal
                            minRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 600,
                        ticks: {
                            stepSize: 100 // Atur langkah sumbu Y
                        },
                        grid: {
                            drawBorder: false, // Hilangkan border sumbu Y
                        }
                    }
                }
            }
        });
    });
</script>
@endpush