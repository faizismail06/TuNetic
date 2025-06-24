@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 text-uppercase">
                    <h4 class="m-0">Master Data</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('manage-petugas.index') }}">Data Petugas</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-warning card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Edit Petugas</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('petugas.profile.update', $petugas->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group mb-3">
                                    <label for="name">Nama</label>
                                    <input type="text" class="form-control" name="name" value="{{ $petugas->name }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" value="{{ $petugas->email }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" name="username" value="{{ $petugas->username }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password">
                                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="nomor">Nomor</label>
                                    <input type="text" class="form-control" name="nomor" value="{{ $petugas->nomor ?? '' }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="tanggal_lahir">Tanggal Lahir</label>
                                    <input type="date" class="form-control" name="tanggal_lahir" value="{{ $petugas->tanggal_lahir ?? '' }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="alamat">Alamat</label>
                                    <input type="text" class="form-control" name="alamat" value="{{ $petugas->alamat ?? '' }}">
                                </div>

                                {{-- Tampilkan SIM lama jika ada --}}
                                @if ($petugas->sim_image)
                                    <div class="form-group mb-2">
                                        <label>Foto SIM Saat Ini:</label><br>
                                        <img src="{{ asset('storage/' . $petugas->sim_image) }}" alt="Foto SIM" width="120" class="img-thumbnail mb-2">
                                    </div>
                                @endif

                                {{-- Upload Foto SIM Baru --}}
                                <div class="form-group mb-3">
                                    <label for="sim_image">Upload Foto SIM Baru (jika ingin mengganti)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="sim_image" name="sim_image" onchange="previewImage(event)">
                                            <label class="custom-file-label" for="sim_image">Pilih Foto</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Rasio 1:1 dan ukuran &lt; 2MB.</small>
                                </div>

                                {{-- PREVIEW GAMBAR YANG BARU DIPILIH --}}
                                <div class="mt-3">
                                    <img id="simImagePreview" src="#" alt="Preview Foto SIM" style="display: none;" width="120" class="img-thumbnail">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="alasan_bergabung">Alasan Bergabung</label>
                                    <input type="text" class="form-control" name="alasan_bergabung" value="{{ $petugas->alasan_bergabung ?? '' }}">
                                </div>

                                <div class="form-group mb-4">
                                    <label for="role">Role</label>
                                    <input type="text" class="form-control" name="role" value="{{ $petugas->role ?? '' }}" readonly>
                                </div>

                                <button type="submit" class="btn btn-primary">Update Petugas</button>
                                <a href="{{ route('manage-petugas.index') }}" class="btn btn-secondary ml-2">Batal</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('simImagePreview');
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endpush
