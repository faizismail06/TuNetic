@extends('layouts.app')

@section('content')

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="content">
        <div class="container-fluid mt-4">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h5 class="m-0">Tambah Data Perhitungan Sampah</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('perhitungan-sampah.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="tanggal_pengangkutan">Tanggal Pengangkutan <span
                                    class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pengangkutan" id="tanggal_pengangkutan" class="form-control"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="id_armada">Armada <span class="text-danger">*</span></label>
                            <select name="id_armada" id="id_armada" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Armada --</option>
                                @foreach($armadas as $armada)
                                    <option value="{{ $armada->id }}">{{ $armada->no_polisi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_rute">Rute <span class="text-danger">*</span></label>
                            <select name="id_rute" id="id_rute" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Rute --</option>
                                @foreach($rutes as $rute)
                                    <option value="{{ $rute->id }}">{{ $rute->nama_rute }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="total_sampah">Berat Sampah (kg) <span class="text-danger">*</span></label>
                            <input type="number" name="total_sampah" id="berat" class="form-control"
                                placeholder="Masukkan berat sampah (kg)" required>
                        </div>
                        <div class="form-group row mt-4">
                            <div class="col-md-4 d-flex justify-content-start" style="gap: 10px;">
                                <button type="submit" class="btn btn-success">Simpan</button>
                                <a href="{{ route('perhitungan-sampah.index') }}" class="btn btn-danger"
                                    style="width: 80px;">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <!-- Pastikan jQuery & DataTables sudah di-include di layout -->
    <script>
        $(document).ready(function () {
            $('#sampah-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('perhitungan-sampah.index') }}',
                columns: [
                    { data: 'no_polisi', name: 'no_polisi' },
                    { data: 'rute', name: 'rute' },
                    { data: 'tanggal_pengangkutan', name: 'tanggal_pengangkutan' },
                    { data: 'berat', name: 'berat' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush