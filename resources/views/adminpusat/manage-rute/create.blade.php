@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 text-uppercase">
                <h4 class="m-0">Tambah Rute</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('manage-rute.index') }}">Data Rute</a></li>
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
                        <h3 class="card-title">Tambah Rute</h3>
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

                        <form id="form-tambah-rute" action="{{ route('manage-rute.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="nama_rute">Nama Rute *</label>
                                <input type="text" class="form-control" id="nama_rute" name="nama_rute" required>
                            </div>

                            <div class="form-group">
                                <label for="wilayah">Wilayah *</label>
                                <input type="text" class="form-control" id="wilayah" name="wilayah" required>
                            </div>

                            <label>TPS</label>
                            <div id="tps-container">
                                <div class="form-group">
                                    <select name="tps[]" class="form-control" required>
                                        <option value="">Pilih TPS</option>
                                        @foreach ($lokasiTps as $lokasi)
                                            <option value="{{ $lokasi->id }}">{{ $lokasi->nama_lokasi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- <button type="button" class="btn btn-success mb-3" id="add-tps-btn">
                                <i class="fas fa-plus"></i>
                            </button> --}}
                            <div class="mb-3">
                                <button type="button" class="btn btn-success" id="add-tps-btn">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-danger" id="remove-tps-btn">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>

                            <div class="form-group">
                                <label for="tps[]">TPST</label>
                                <select name="tps[]" class="form-control">
                                    <option value="">Pilih TPST</option>
                                    @foreach ($lokasiTps as $lokasi)
                                        <option value="{{ $lokasi->id }}">{{ $lokasi->nama_lokasi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tps[]">TPA</label>
                                <select name="tps[]" class="form-control">
                                    <option value="">Pilih TPA</option>
                                    @foreach ($lokasiTps as $lokasi)
                                        <option value="{{ $lokasi->id }}">{{ $lokasi->nama_lokasi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="button" class="btn btn-success" id="btn-submit-form">Simpan Perubahan</button>
                            <a href="{{ route('manage-rute.index') }}" class="btn btn-danger">Batal</a>
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
            // Tombol tambah TPS
            document.getElementById('add-tps-btn').addEventListener('click', function () {
                const container = document.getElementById('tps-container');
                const select = container.querySelector('select').cloneNode(true);
                const wrapper = document.createElement('div');
                wrapper.className = 'form-group';
                wrapper.appendChild(select);
                container.appendChild(wrapper);
            });

            // Tombol hapus TPS
            document.getElementById('remove-tps-btn').addEventListener('click', function () {
                const container = document.getElementById('tps-container');
                const selects = container.querySelectorAll('.form-group');
                if (selects.length > 1) {
                    container.removeChild(selects[selects.length - 1]);
                }
            });

            // Trigger konfirmasi saat klik tombol simpan
            document.getElementById('btn-submit-form').addEventListener('click', function () {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah Anda yakin ingin menambah rute ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-tambah-rute').submit();
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
