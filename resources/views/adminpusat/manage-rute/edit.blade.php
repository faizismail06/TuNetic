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

                            <div class="form-group mt-4">
                                <!-- Label dan tombol tambah sejajar -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="tps" class="mb-0">TPS</label>
                                    <button type="button" class="btn btn-success" id="add-tps-btn">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>

                                <!-- Wrapper baris TPS -->
                                <div id="tps-input-wrapper">
                                    @forelse ($rute->tps->where('tipe', 'TPS') as $tps)
                                        <div class="tps-row input-group mb-2">
                                            <select name="tps[]" class="form-control" required>
                                                <option value="">Pilih TPS</option>
                                                @foreach($lokasiTps as $lokasi)
                                                    @if($lokasi->tipe === 'TPS')
                                                        <option value="{{ $lokasi->id }}" {{ $tps->id == $lokasi->id ? 'selected' : '' }}>
                                                            {{ $lokasi->nama_lokasi }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-danger remove-tps-btn">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="tps-row input-group mb-2">
                                            <select name="tps[]" class="form-control" required>
                                                <option value="">Pilih TPS</option>
                                                @foreach($lokasiTps as $lokasi)
                                                    @if($lokasi->tipe === 'TPS')
                                                        <option value="{{ $lokasi->id }}">{{ $lokasi->nama_lokasi }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-danger remove-tps-btn">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
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
        const wrapper = document.getElementById('tps-input-wrapper');
        const addBtn = document.getElementById('add-tps-btn');

        function createTpsRow() {
            const div = document.createElement('div');
            div.className = 'tps-row input-group mb-2';
            div.innerHTML = `
                <select name="tps[]" class="form-control" required>
                    <option value="">Pilih TPS</option>
                    @foreach($lokasiTps as $lokasi)
                        @if($lokasi->tipe === 'TPS')
                            <option value="{{ $lokasi->id }}">{{ $lokasi->nama_lokasi }}</option>
                        @endif
                    @endforeach
                </select>
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger remove-tps-btn">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            `;
            return div;
        }

        // Tambah TPS
        addBtn.addEventListener('click', () => {
            const newRow = createTpsRow();
            wrapper.appendChild(newRow);
        });

        // Hapus TPS tertentu
        wrapper.addEventListener('click', function (e) {
            if (e.target.closest('.remove-tps-btn')) {
                const row = e.target.closest('.tps-row');
                if (wrapper.querySelectorAll('.tps-row').length > 1) {
                    row.remove();
                }
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