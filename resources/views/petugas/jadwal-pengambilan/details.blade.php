{{-- resources/views/jemputan-harian.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <h3 class="fw-bold">Rute Jemputan Sampah Hari Selasa</h3>

        <div class="mt-4">
            <h5 class="fw-bold">Informasi Petugas</h5>
            <div class="d-flex align-items-start mt-3">
                <img src="{{ asset('images/avatar.png') }}" alt="Foto Petugas" class="rounded-circle me-4" width="100"
                    height="100">
                <div>
                    <p><strong>Nama</strong> : Tomi</p>
                    <p><strong>Armada</strong> : H 1234 AB</p>
                    <p><strong>Tugas Hari ini</strong> : <strong>5 Lokasi Jemput Sampah</strong></p>
                    <div class="mt-2 d-flex flex-wrap gap-3">
                        <span class="badge bg-success">TPS Banyumanik</span>
                        <span class="badge bg-success">TPS Tembalang</span>
                        <span class="badge bg-success">TPS Pedurungan</span>
                        <span class="badge bg-success">TPS Semarang Barat</span>
                        <span class="badge bg-success">TPS Jatibarang</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-5">
            <img src="{{ asset('images/rute-map.png') }}" alt="Peta Rute" class="img-fluid rounded shadow">
        </div>

        <div>
            <h5 class="fw-bold">Progress Penjemputan Sampah</h5>
            <div class="table-responsive mt-3">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>TPS</th>
                            <th>Jalan</th>
                            <th>Armada</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>TPS Banyumanik</td>
                            <td>Jl. Setiabudi, Banyumanik</td>
                            <td>H 1234 AB</td>
                            <td>10.00</td>
                            <td>10.45</td>
                            <td><span class="badge bg-success">Selesai</span></td>
                            <td>
                                <button class="btn btn-primary btn-sm me-2">Mulai Jemput</button>
                                <button class="btn btn-success btn-sm">Selesai</button>
                            </td>
                        </tr>
                        <tr>
                            <td>TPS Tembalang</td>
                            <td>Jl. Tembalang Raya, dekat Undip</td>
                            <td>H 1234 AB</td>
                            <td>10.45</td>
                            <td>-</td>
                            <td><span class="badge bg-warning text-dark">Proses</span></td>
                            <td>
                                <button class="btn btn-primary btn-sm me-2">Mulai Jemput</button>
                                <button class="btn btn-success btn-sm">Selesai</button>
                            </td>
                        </tr>
                        <tr>
                            <td>TPS Pedurungan</td>
                            <td>Jl. Wolter Monginsidi, Pedurungan</td>
                            <td>H 1234 AB</td>
                            <td>-</td>
                            <td>-</td>
                            <td><span class="badge bg-danger">Belum</span></td>
                            <td>
                                <button class="btn btn-primary btn-sm me-2">Mulai Jemput</button>
                                <button class="btn btn-success btn-sm">Selesai</button>
                            </td>
                        </tr>
                        <tr>
                            <td>TPS Semarang Barat</td>
                            <td>Jl. Pamularsih, Semarang Barat</td>
                            <td>H 1234 AB</td>
                            <td>-</td>
                            <td>-</td>
                            <td><span class="badge bg-danger">Belum</span></td>
                            <td>
                                <button class="btn btn-primary btn-sm me-2">Mulai Jemput</button>
                                <button class="btn btn-success btn-sm">Selesai</button>
                            </td>
                        </tr>
                        <tr>
                            <td>TPA Jatibarang</td>
                            <td>TPA utama di Semarang</td>
                            <td>H 1234 AB</td>
                            <td>-</td>
                            <td>-</td>
                            <td><span class="badge bg-danger">Belum</span></td>
                            <td>
                                <button class="btn btn-success btn-sm">Selesai</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
