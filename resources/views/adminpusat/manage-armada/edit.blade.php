@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 text-uppercase">
                <h4 class="m-0">Edit Armada</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('manage-armada.index') }}">Data Armada</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Edit Armada</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="form-edit-armada" action="{{ route('manage-armada.update', $armada->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="jenis_kendaraan">Jenis Kendaraan *</label>
                                <input type="text" class="form-control" id="jenis_kendaraan" name="jenis_kendaraan" value="{{ old('jenis_kendaraan', $armada->jenis_kendaraan) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="no_polisi">Nomor Polisi *</label>
                                <input type="text" class="form-control" id="no_polisi" name="no_polisi" value="{{ old('no_polisi', $armada->no_polisi) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="merk_kendaraan">Merek Kendaraan *</label>
                                <input type="text" class="form-control" id="merk_kendaraan" name="merk_kendaraan" value="{{ old('merk_kendaraan', $armada->merk_kendaraan) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="kapasitas">Kapasitas *</label>
                                <input type="number" class="form-control" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $armada->kapasitas) }}" required>
                            </div>

                            <button type="button" class="btn btn-success" id="btn-update-form">Simpan Perubahan</button>
                            <a href="{{ route('manage-armada.index') }}" class="btn btn-danger">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Konfirmasi update
            document.getElementById('btn-update-form').addEventListener('click', function () {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah Anda yakin ingin menyimpan perubahan?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-edit-armada').submit();
                    }
                });
            });

            // Pesan sukses jika ada
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#28a745'
                });
            @endif
        });
    </script>
@endpush