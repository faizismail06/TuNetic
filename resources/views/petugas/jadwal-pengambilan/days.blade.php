@extends('components.navbar')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Text:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .day-btn {
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn-success {
            background-color: #F7B928;
        }

        .btn-warning {
            background-color: #A0A0A0;
            color: white;
        }

        .btn-secondary {
            background-color: #24A148;
            color: white;
        }

        .schedule-section {
            min-height: 80vh;
            display: flex;
            align-items: center;
        }

        .schedule-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .minggu-btn-container {
            display: flex;
            justify-content: flex-start;
            padding-left: 50%;
            transform: translateX(-50%);
        }

        @media (max-width: 992px) {
            .schedule-section {
                padding: 40px 20px;
            }
        }
    </style>
    <section style="display: flex; align-items: center; justify-content: center; padding: 1px 20px; gap: 40px;">
        <section class="schedule-section">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-5">
                <!-- Left: Text + Image -->
                <div class="d-flex flex-column gap-2">
                    <!-- Judul + Deskripsi -->
                    <div>
                        <h1 class="fw-bold fs-1">Jadwal Pengambilan Sampah</h1>
                        <p class="fs-5 text-secondary">
                            Ikuti rute yang sudah ditentukan dan pastikan semua sampah terangkut
                        </p>
                    </div>

                    <!-- Konten Utama: Ilustrasi + Tombol -->
                    <div class="d-flex align-items-center gap-1">
                        <!-- Ilustrasi Kiri -->
                        <div class="d-flex align-items-start gap-1">
                            <img src="{{ asset('assets/images/petugas/iconPetugas1.png') }}" alt="Ilustrasi Petugas"
                                class="img-fluid" style="max-width:90%; height:auto;">
                        </div>

                        @php
                            $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
                        @endphp

                        <div class="container px-0" style="min-width:300px; max-width:350px;">
                            <div class="row g-3">
                                @foreach ($hariList as $hari)
                                    <div class="col-12 col-sm-6">
                                        <a href="{{ in_array($hari, $jadwalHari) ? route('jadwal-pengambilan.auto-tracking', ['day' => $hari]) : '#' }}"
                                            class="btn w-100 py-3 px-4 fs-5 {{ strtolower($todayName) == $hari ? 'btn-success' : 'btn-secondary' }}"
                                            {{ in_array($hari, $jadwalHari) ? '' : 'disabled' }}>
                                            {{ ucfirst($hari) }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </div>
        </section>
    </section>
@endsection
