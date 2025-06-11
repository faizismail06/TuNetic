<div class="card card-transparent border-0">
    <div class="mb-2 bg-transparent border-0 rute-title">
        <h2>Rute Jemputan Sampah Hari {{ ucfirst($hari ?? (request()->day ?? 'Selasa')) }}</h2>
    </div>

    <div class="mb-3 bg-transparent border-0 rute-title">
        <h3>Informasi Petugas</h3>
    </div>
    <!-- info-petugas diletakkan DI BAWAH judul -->
    <div class="mb-3 info-petugas">
        <div class="avatar">
            <img src="{{ asset('assets/images/avatars/avatar-1.png') }}" alt="Foto Petugas">
        </div>

        <div class="info-details">
            <div class="info-row">
                <div class="info-label">Nama</div>
                <div class="info-value">: {{ $petugas->name ?? (Auth::user()->name ?? '-') }}</div>
            </div>

            @foreach ($jadwalOperasional as $jadwal)
                <div class="info-row">
                    <div class="info-label">Armada</div>
                    <div class="info-value">: {{ $jadwal->armada->no_polisi ?? '-' }}</div>
                </div>
            @endforeach


            <div class="info-row">
                <div class="info-label">Tugas Hari ini</div>
                <div class="info-value">: {{ count($tps_locations ?? []) }} Lokasi Jemput Sampah</div>
            </div>


            <div class="lokasi-list">
                @foreach ($tps_locations ?? [] as $tps)
                    <div class="tps-badge">
                        <span class="tps-marker"
                            style="background-color:
                            @if ($tps['status'] == 'Selesai') #6c757d
                            @elseif($tps['status'] == 'Progress') #28a745
                            @else #17a2b8 @endif"></span>
                        <span class="tps-name">{{ $tps['nama'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>



<style>
    .info-petugas-container {
        font-family: Arial, sans-serif;
        margin-bottom: 20px;
    }

    .header-box {
        border: 2px solid #0d6efd;
        border-radius: 5px;
        padding: 10px 15px;
        margin-bottom: 20px;
        display: inline-block;
    }

    .header-box h2 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: bold;
    }

    .info-petugas {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 20px;
        background-color: #e9ecef;
        border: 1px solid #dee2e6;
    }

    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .info-details {
        flex: 1;
        min-width: 200px;
    }

    .info-row {
        margin-bottom: 8px;
        display: flex;
    }

    .info-label {
        width: 120px;
        font-weight: 500;
    }

    .info-value {
        flex: 1;
    }

    .lokasi-list {
        margin-top: 10px;
    }

    .tps-badge {
        display: inline-flex;
        align-items: center;
        background-color: #fff;
        margin-bottom: 8px;
        margin-right: 8px;
    }

    .tps-marker {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background-color: #28a745;
        margin-right: 5px;
        display: inline-block;
    }

    .tps-name {
        font-size: 14px;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, .125);
    }

    .content-wrapper {
        margin-top: 30px;
        /* Sesuaikan dengan tinggi navbar */
    }

    .card-transparent {
        background-color: transparent;
        border: none;
    }

    .rute-title {
        font-size: 1.6rem;
        font-weight: bold;

    }
</style>
