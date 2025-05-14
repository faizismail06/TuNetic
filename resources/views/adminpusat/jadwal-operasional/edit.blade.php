@extends('layouts.app')

@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
    <script>
        function nextStep() {
            document.getElementById('step-1').style.display = 'none';
            document.getElementById('step-2').style.display = 'block';
        }

        function prevStep() {
            document.getElementById('step-2').style.display = 'none';
            document.getElementById('step-1').style.display = 'block';
        }
    </script>

<script>
    let petugasIndex = 0;

    function tambahPetugas() {
        const container = document.getElementById('petugasList');
        const currentIndex = petugasIndex;

        const row = document.createElement('div');
        row.className = 'form-group';
        row.id = 'petugasRow-' + currentIndex;

        row.innerHTML =  `
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Petugas</label>
                        <select name="petugas[${currentIndex}][id_petugas]" class="form-control">
                            @foreach ($petugas as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tugas</label>
                        <select name="petugas[${currentIndex}][tugas]" class="form-control">
                            <option value="1">Driver</option>
                            <option value="2">Picker</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end mb-3">
                    <button type="button" class="btn btn-danger" onclick="hapusPetugas(${currentIndex})">Hapus</button>
                </div>
            </div>
        `;

        container.appendChild(row);
        petugasIndex++;
    }

    function hapusPetugas(index) {
        const row = document.getElementById('petugasRow-' + index);
        if (row) row.remove();
    }
</script>

@endpush

@section('content')
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        {{-- <title>Edit Jadwal Operasional Armada</title> --}}
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f8f9fa;
            }

            .main-container {
                padding: 20px;
                max-width: 1000px;
                margin: 0 auto;
            }

            .page-title {
                font-size: 24px;
                font-weight: 600;
                margin-bottom: 30px;
                color: #333;
            }

            .form-group label {
                font-weight: 500;
                font-size: 14px;
                color: #444;
            }

            .form-control {
                border-radius: 4px;
                padding: 5px 15px;
                border: 1px solid #ced4da;
                font-size: 14px;
            }

            .form-control:focus {
                border-color: #28a745;
                box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
            }

            .btn-success {
                background-color: #28a745;
                border-color: #28a745;
            }

            .btn-success:hover {
                background-color: #218838;
                border-color: #1e7e34;
            }

            .btn-outline-primary {
                color: #28a745;
                border-color: #28a745;
            }

            .btn-outline-primary:hover {
                background-color: #28a745;
                border-color: #28a745;
                color: white;
            }

            .petugas-container {
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 15px;
                margin-bottom: 15px;
                background-color: #f9f9f9;
            }

            .petugas-header {
                font-weight: 600;
                margin-bottom: 15px;
                border-bottom: 1px solid #eee;
                padding-bottom: 10px;
            }

            .btn-danger {
                background-color: #dc3545;
                border-color: #dc3545;
            }

            .action-buttons {
                margin-top: 30px;
            }

            .form-section {
                margin-bottom: 30px;
            }

            .form-section-title {
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 1px solid #eee;
            }
        </style>
    </head>

    <body>
        <div class="main-container">
            <h2 class="page-title">EDIT JADWAL OPERASIONAL</h2>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('jadwal-operasional.update', $jadwalOperasional->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <!-- STEP 1: Jadwal Operasional -->
                        <div id="step-1">
                            <div class="form-section">
                                <h4 class="form-section-title">Jadwal Operasional</h4>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Hari</label>
                                        <select name="id_jadwal" class="form-control" required>
                                            @foreach ($jadwals as $jadwal)
                                                <option value="{{ $jadwal->id }}">{{ $jadwal->hari }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="tanggal">Tanggal</label>
                                        <input type="date" name="tanggal" class="form-control" required
                                            value="{{ old('tanggal', $jadwalOperasional->tanggal ?? '') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Armada</label>
                                        <select name="id_armada" class="form-control">
                                            @foreach ($armadas as $armada)
                                                <option value="{{ $armada->id }}"
                                                    {{ $jadwalOperasional->id_armada == $armada->id ? 'selected' : '' }}>
                                                    {{ $armada->jenis_kendaraan }} - {{ $armada->no_polisi }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label>Rute</label>
                                        <select name="id_rute" class="form-control" required>
                                            @foreach ($rutes as $rute)
                                                <option value="{{ $rute->id }}"
                                                    {{ $jadwalOperasional->id_rute == $rute->id ? 'selected' : '' }}>
                                                    {{ $rute->nama_rute }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Jam Aktif</label>
                                        <input type="time" name="jam_aktif" class="form-control" required>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label for="status">Status Jadwal</label>
                                        <select name="status" class="form-control">
                                            <option value="">-- Pilih Status --</option>
                                            @foreach (\App\Models\JadwalOperasional::getStatusLabels() as $key => $label)
                                                <option value="{{ $key }}"
                                                    {{ old('status', $jadwalOperasional->status ?? '') == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary mt-3 mr-2" onclick="nextStep()">Lanjut</button>
                            <a href="{{ route('jadwal-operasional.index') }}" class="btn btn-danger mt-3">Batal</a>
                        </div>
                        <div id="step-2" style="display: none;">
                            <!-- Bagian Petugas -->
                            <div class="form-section">
                                <h4 class="form-section-title">Penugasan Petugas</h4>

                                <div id="petugasList">
                                    <!-- Petugas 1 -->
                                    <div class="petugas-item mb-4" id="petugas-1">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Petugas</label>
                                                    <select name="petugas[0][id_petugas]" class="form-control">
                                                        @foreach ($petugas as $p)
                                                            <option value="{{ $p->id }}">{{ $p->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tugas</label>
                                                    <select name="petugas[0][tugas]" class="form-control">
                                                        <option value="1">Driver</option>
                                                        <option value="2">Picker</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Petugas</label>
                                                    <select name="petugas[1][id_petugas]" class="form-control">
                                                        @foreach ($petugas as $p)
                                                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Tugas</label>
                                                    <select name="petugas[1][tugas]" class="form-control">
                                                        <option value="1">Driver</option>
                                                        <option value="2">Picker</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end mb-3">
                                                <button type="button" class="btn btn-danger" onclick="hapusPetugas(0)">Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-outline-primary" id="btnTambahPetugas" onclick="tambahPetugas()">+ Tambah
                                        Petugas Baru</button>
                                </div>
                            </div>
                            <div class="row action-buttons">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary mr-2" onclick="prevStep()">Kembali</button>
                                    <button type="submit" class="btn btn-success mr-2">Simpan</button>
                                    <a href="{{ route('jadwal-operasional.index') }}" class="btn btn-danger">Batal</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>

    </html>
@endsection
