@extends('layouts.app')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@push('js')
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
        let petugasIndex = 1; // karena petugas[0] sudah ada

        function tambahPetugas() {
            const container = document.getElementById('petugasList');

            const row = document.createElement('div');
            row.className = 'form-row mb-2';
            row.id = 'petugasRow-' + petugasIndex;

            row.innerHTML = `
                <select name="petugas[${petugasIndex}][id_petugas]" class="form-control">
                    @foreach($petugas as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>

                <select name="petugas[${petugasIndex}][tugas]" class="form-control ml-2">
                    <option value="1">Driver</option>
                    <option value="2">Picker</option>
                </select>

                <button type="button" class="btn btn-sm btn-danger ml-2" onclick="hapusPetugas(${petugasIndex})">X</button>
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
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 text-uppercase">
                <h4 class="m-0">Tambah Jadwal Operasional</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    {{-- Breadcrumb opsional --}}
                </ol>
            </div>
        </div>
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success card-outline">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="m-0">Form Tambah Jadwal Operasional</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('jadwal-operasional.update', $jadwalOperasional->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <!-- STEP 1: Jadwal Operasional -->
                            <div id="step-1">
                                <h5>Jadwal Operasional</h5>

                                <div class="form-group">
                                    <label>Jadwal (Tanggal & Hari)</label>
                                    <select name="id_jadwal" class="form-control" required>
                                        @foreach ($jadwals as $jadwal)
                                            <option value="{{ $jadwal->id }}">{{ $jadwal->hari }} - {{ $jadwal->tanggal }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" required value="{{ old('tanggal', $jadwalOperasional->tanggal ?? '') }}">
                                </div>

                                <div class="form-group">
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

                                <div class="form-group">
                                    <label>Rute TPS</label>
                                    <select name="id_rute_tps" class="form-control" required>
                                        @foreach ($ruteTps as $rute)
                                            <option value="{{ $rute->id }}"{{ $jadwalOperasional->id_rute_tps == $rute->id ? 'selected' : '' }}>Rute #{{ $rute->id }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Jam Aktif</label>
                                    <input type="time" name="jam_aktif" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="status">Status Jadwal</label>
                                    <select name="status" class="form-control">
                                        <option value="">-- Pilih Status --</option>
                                        @foreach (\App\Models\JadwalOperasional::getStatusLabels() as $key => $label)
                                            <option value="{{ $key }}" {{ old('status', $jadwalOperasional->status ?? '') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <button type="button" class="btn btn-primary mt-3" onclick="nextStep()">Lanjut</button>
                            </div>

                            <!-- STEP 2: Penugasan Petugas -->
                            <div id="step-2" style="display: none;">
                                <h5>Penugasan Petugas</h5>

                                <div id="petugasList">
                                    @foreach ($jadwalOperasional->penugasanPetugas as $index => $penugasan)
                                        <div class="form-row mb-2" id="petugasRow-{{ $index }}">
                                            <select name="petugas[{{ $index }}][id_petugas]" class="form-control">
                                                @foreach ($petugas as $p)
                                                    <option value="{{ $p->id }}"
                                                        {{ $penugasan->id_petugas == $p->id ? 'selected' : '' }}>
                                                        {{ $p->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <select name="petugas[{{ $index }}][tugas]" class="form-control ml-2">
                                                <option value="1" {{ $penugasan->tugas == 'Driver' ? 'selected' : '' }}>Driver</option>
                                                <option value="2" {{ $penugasan->tugas == 'Picker' ? 'selected' : '' }}>Picker</option>
                                            </select>

                                            <button type="button" class="btn btn-sm btn-danger ml-2" onclick="hapusPetugas({{ $index }})">X</button>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="button" class="btn btn-sm btn-outline-primary mb-3" onclick="tambahPetugas()">+ Tambah Petugas</button>

                                <div class="form-group">
                                    <button type="button" class="btn btn-secondary" onclick="prevStep()">Kembali</button>
                                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>

                    </div> <!-- /.card-body -->
                </div> <!-- /.card -->
            </div>
        </div>
    </div>
</div>
@endsection

