@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 text-uppercase">
                <h4 class="m-0">Edit Rute</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('manage-rute.index') }}">Data Rute</a></li>
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
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Edit Rute</h3>
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

                        <form id="form-edit-rute" action="{{ route('manage-rute.update', $rute->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="nama_rute">Nama Rute *</label>
                                <input type="text" class="form-control" id="nama_rute" name="nama_rute" value="{{ $rute->nama_rute }}" required>
                            </div>

                            <div class="form-group">
                                <label for="wilayah">Wilayah *</label>
                                <input type="text" class="form-control" id="wilayah" name="wilayah" value="{{ $rute->wilayah }}" required>
                            </div>

                            <div id="tps-input-wrapper">
                                <label for="tps">TPS</label>
                                @foreach ($rute->tps->where('tipe', 'TPS') as $tps)
                                    <div class="tps-row input-group mb-2">
                                        <select name="tps[]" class="form-control" required>
                                            <option value="">Pilih TPS</option>
                                            @foreach($lokasiTps as $lokasi)
                                                @if($lokasi->tipe == 'TPS')
                                                    <option value="{{ $lokasi->id }}" {{ $lokasi->id == $tps->id ? 'selected' : '' }}>{{ $lokasi->nama_lokasi }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                                @if($rute->tps->where('tipe', 'TPS')->isEmpty())
                                    <div class="tps-row input-group mb-2">
                                        <select name="tps[]" class="form-control" required>
                                            <option value="">Pilih TPS</option>
                                            @foreach($lokasiTps as $lokasi)
                                                @if($lokasi->tipe == 'TPS')
                                                    <option value="{{ $lokasi->id }}">{{ $lokasi->nama_lokasi }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <button type="button" class="btn btn-success" id="add-tps-btn">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-danger" id="remove-tps-btn">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>

                            <div class="form-group">
                                <label for="tpst">TPST</label>
                                <select name="tps[]" class="form-control">
                                    <option value="">Pilih TPST</option>
                                    @foreach($lokasiTps as $lokasi)
                                        @if($lokasi->tipe == 'TPST')
                                            <option value="{{ $lokasi->id }}" 
                                                {{ $rute->tps->where('tipe', 'TPST')->pluck('id')->contains($lokasi->id) ? 'selected' : '' }}>
                                                {{ $lokasi->nama_lokasi }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tpa">TPA</label>
                                <select name="tps[]" class="form-control">
                                    <option value="">Pilih TPA</option>
                                    @foreach($lokasiTps as $lokasi)
                                        @if($lokasi->tipe == 'TPA')
                                            <option value="{{ $lokasi->id }}" 
                                                {{ $rute->tps->where('tipe', 'TPA')->pluck('id')->contains($lokasi->id) ? 'selected' : '' }}>
                                                {{ $lokasi->nama_lokasi }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <button type="button" class="btn btn-warning" id="btn-submit-form">Simpan Perubahan</button>
                            <a href="{{ route('manage-rute.index') }}" class="btn btn-secondary">Batal</a>
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
        const addBtn = document.getElementById('add-tps-btn');
        const removeBtn = document.getElementById('remove-tps-btn');
        const wrapper = document.getElementById('tps-input-wrapper');

        addBtn.addEventListener('click', function () {
            const rows = wrapper.querySelectorAll('.tps-row');
            const lastRow = rows[rows.length -1];
            const newRow = lastRow.cloneNode(true);
            const select = newRow.querySelector('select');

            select.value = "";
            wrapper.appendChild(newRow);
        });

        removeBtn.addEventListener('click', function () {
            const rows = wrapper.querySelectorAll('.tps-row');
            if (rows.length > 1) {
                wrapper.removeChild(rows[rows.length - 1]);
            }
        });

        document.getElementById('btn-submit-form').addEventListener('click', function () {
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin menyimpan perubahan?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-edit-rute').submit();
                }
            });
        });

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