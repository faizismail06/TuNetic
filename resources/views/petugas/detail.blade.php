@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 text-uppercase">
                    <h4 class="m-0">Kelola Petugas</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('petugas.index') }}">Data Petugas</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <div class="row justify-content-center mb-4">
                                <div class="col-md-4 text-center">
                                    <div class="profile-image">
                                        <i class="fas fa-user fa-6x"></i>
                                    </div>
                                    <h6 class="mt-2">{{ $petugas->name ?? 'Nama Petugas' }}</h6>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" value="{{ $petugas->name ?? '' }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" value="{{ $petugas->username ?? '' }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="nomor">No Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nomor" value="{{ $petugas->nomor ?? '' }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" value="{{ $petugas->email ?? '' }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="text" class="form-control" id="tanggal_lahir" value="{{ $petugas->tanggal_lahir ?? '' }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="provinsi">Alamat</label>
                                <input type="text" class="form-control" id="alamat" value="{{ $petugas->alamat ?? '' }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="sim_petugas">SIM Petugas</label>
                                <div>
                                    @if ($petugas->sim_image)
                                        <img src="{{ asset($petugas->sim_image) }}" alt="Foto SIM" width="120" class="img-thumbnail mb-2">
                                    @else
                                        <p>Tidak ada gambar SIM</p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="alasan_bergabung">Alasan Bergabung</label>
                                <textarea class="form-control" id="alasan_bergabung" rows="3" readonly>{{ $petugas->alasan_bergabung ?? '' }}</textarea>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12 d-flex gap-2">
                                    <!-- @if ($petugas->status !== 'Disetujui' && $petugas->status !== 'Ditolak')
                                        <form action="{{ route('verifikasi.user', $petugas->user_id) }}" method="POST" onsubmit="return confirm('Setujui petugas ini?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-sm mr-2">Setujui</button>
                                        </form>

                                        <button type="button" class="btn btn-danger btn-sm btn-tolak mr-2">Tolak</button>
                                    @endif -->

                                    @if ($petugas->status !== 'Disetujui' && $petugas->status !== 'Ditolak')
                                    <button type="button" class="btn btn-success btn-sm mr-2 btn-setujui">Setujui</button>

                                    <form id="form-verifikasi" action="{{ route('verifikasi.user', $petugas->user_id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('PUT')
                                    </form>

                                    <button type="button" class="btn btn-danger btn-sm btn-tolak mr-2">Tolak</button>
                                @endif


                                    <a href="{{ route('manage-petugas.index') }}" class="btn btn-secondary btn-sm ml-2">Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .profile-image {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background-color: #ddd;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #aaa;
        }

        .profile-image i {
            font-size: 5em;
        }
    </style>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script> 
    document.addEventListener('DOMContentLoaded', function () {
        const btnSetujui = document.querySelector('.btn-setujui');
        const btnTolak = document.querySelector('.btn-tolak');

        // Jika tombol Setujui ditekan
        if (btnSetujui) {
            btnSetujui.addEventListener('click', function () {
                Swal.fire({
                    title: 'Yakin menyetujui?',
                    text: 'Petugas akan disetujui dan email verifikasi akan dikirim.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Setujui',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-verifikasi').submit();
                    }
                });
            });
        }

        // Jika tombol Tolak ditekan
        if (btnTolak) {
            btnTolak.addEventListener('click', function () {
                Swal.fire({
                    title: 'Yakin menolak?',
                    text: 'Petugas akan ditolak dan tidak dapat diverifikasi.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tolak',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateStatus('Ditolak');
                    }
                });
            });
        }

        function updateStatus(status) {
            fetch("{{ route('petugas.updateStatus', $petugas->id) }}", {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil',
                        text: `Status diubah ke ${status}`,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "{{ route('manage-petugas.index') }}";
                    });
                } else {
                    Swal.fire('Gagal', data.message ?? 'Terjadi kesalahan.', 'error');
                }
            }).catch(err => {
                Swal.fire('Gagal', 'Terjadi kesalahan server.', 'error');
            });
        }
    });
</script>
@endpush

