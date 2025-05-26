@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 text-uppercase">
                <h4 class="m-0">Tambah Armada</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('manage-armada.index') }}">Data Armada</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
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
                        <h3 class="card-title">Tambah Armada</h3>
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

                        <form id="form-tambah-armada" action="{{ route('manage-armada.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="jenis_kendaraan">Jenis Kendaraan *</label>
                                <input type="text" class="form-control" id="jenis_kendaraan" name="jenis_kendaraan" required>
                            </div>

                            <div class="form-group">
                                <label for="no_polisi">Nomor Polisi *</label>
                                <input type="text" class="form-control" id="no_polisi" name="no_polisi" required>
                            </div>

                            <div class="form-group">
                                <label for="merk_kendaraan">Merek Kendaraan *</label>
                                <input type="text" class="form-control" id="merk_kendaraan" name="merk_kendaraan" required>
                            </div>

                            <div class="form-group">
                                <label for="kapasitas">Kapasitas *</label>
                                <input type="number" class="form-control" id="kapasitas" name="kapasitas" required>
                            </div>

                            <button type="button" class="btn btn-success" id="btn-submit-form">Simpan Perubahan</button>
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
            // Trigger konfirmasi saat klik tombol simpan
            document.getElementById('btn-submit-form').addEventListener('click', function () {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah Anda yakin ingin menambah Armada ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-tambah-armada').submit();
                    }
                });
            });

            // Sweet alert untuk pesan sukses
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