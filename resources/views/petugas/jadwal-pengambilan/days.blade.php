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
            background-color: #24A148;
        }

        .btn-warning {
            background-color: #F7B928;
            color: white;
        }

        .btn-secondary {
            background-color: #A0A0A0;
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
    <section style="display: flex; align-items: center; justify-content: center; padding: 1px 20px;gap: 40px;">
        <section class="schedule-section">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-5">
                <!-- Left: Text + Image -->
                <div class="flex-grow-1" style="min-width: 300px; max-width: 600px;">
                    <h1 style="font-weight: 700; font-size: 2.5rem;">Jadwal Pengambilan Sampah</h1>
                    <p style="font-size: 1.1rem; color: #555;">
                        Ikuti rute yang sudah ditentukan dan pastikan semua sampah terangkut
                    </p>
                    <img src="{{ asset('assets/images/petugas/iconPetugas1.png') }}" alt="Ilustrasi Petugas"
                        style="width: 100%; height: auto; max-width: 500px;">
                </div>

                <!-- Right: Day Buttons -->
                <div class="d-flex flex-column gap-3 justify-content-center" style="min-width: 300px; max-width: 350px;">
                    <div class="d-flex gap-3">
                        <!-- Fixed routes using proper day parameters -->
                        <a href="{{ in_array('senin', $jadwalHari) ? route('jadwal-pengambilan.auto-tracking', ['day' => 'senin']) : '#' }}"
                            class="btn day-btn {{ strtolower($todayName) == 'senin' ? 'btn-success' : 'btn-secondary' }} w-100 py-3 fs-5"
                            {{ !in_array('senin', $jadwalHari) ? 'disabled' : '' }}>
                            Senin
                        </a>
                        <a href="{{ in_array('selasa', $jadwalHari) ? route('jadwal-pengambilan.auto-tracking', ['day' => 'selasa']) : '#' }}"
                            class="btn day-btn {{ strtolower($todayName) == 'selasa' ? 'btn-success' : 'btn-secondary' }} w-100 py-3 fs-5"
                            {{ !in_array('selasa', $jadwalHari) ? 'disabled' : '' }}>
                            Selasa
                        </a>
                    </div>
                    <div class="d-flex gap-3">
                        <a href="{{ in_array('rabu', $jadwalHari) ? route('jadwal-pengambilan.auto-tracking', ['day' => 'rabu']) : '#' }}"
                            class="btn day-btn {{ strtolower($todayName) == 'rabu' ? 'btn-success' : 'btn-secondary' }} w-100 py-3 fs-5"
                            {{ !in_array('rabu', $jadwalHari) ? 'disabled' : '' }}>
                            Rabu
                        </a>
                        <a href="{{ in_array('kamis', $jadwalHari) ? route('jadwal-pengambilan.auto-tracking', ['day' => 'kamis']) : '#' }}"
                            class="btn day-btn {{ strtolower($todayName) == 'kamis' ? 'btn-success' : 'btn-secondary' }} w-100 py-3 fs-5"
                            {{ !in_array('kamis', $jadwalHari) ? 'disabled' : '' }}>
                            Kamis
                        </a>
                    </div>
                    <div class="d-flex gap-3">
                        <a href="{{ in_array('jumat', $jadwalHari) ? route('jadwal-pengambilan.auto-tracking', ['day' => 'jumat']) : '#' }}"
                            class="btn day-btn {{ strtolower($todayName) == 'jumat' ? 'btn-success' : 'btn-secondary' }} w-100 py-3 fs-5"
                            {{ !in_array('jumat', $jadwalHari) ? 'disabled' : '' }}>
                            Jumat
                        </a>
                        <a href="{{ in_array('sabtu', $jadwalHari) ? route('jadwal-pengambilan.auto-tracking', ['day' => 'sabtu']) : '#' }}"
                            class="btn day-btn {{ strtolower($todayName) == 'sabtu' ? 'btn-success' : 'btn-secondary' }} w-100 py-3 fs-5"
                            {{ !in_array('sabtu', $jadwalHari) ? 'disabled' : '' }}>
                            Sabtu
                        </a>
                    </div>
                    <div class="d-flex">
                        <a href="{{ in_array('minggu', $jadwalHari) ? route('jadwal-pengambilan.auto-tracking', ['day' => 'minggu']) : '#' }}"
                            class="btn day-btn {{ strtolower($todayName) == 'minggu' ? 'btn-success' : 'btn-secondary' }} w-50 py-3 fs-5"
                            {{ !in_array('minggu', $jadwalHari) ? 'disabled' : '' }}>
                            Minggu
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection
