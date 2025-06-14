@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 text-uppercase">
                    <h4 class="m-0">Tambah Petugas</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('petugas.index') }}">Data Petugas</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-11">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Tambah Petugas</h3>
                        </div>
                        <div class="card-body">

                            {{-- Tampilkan error validasi --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('manage-petugas.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="user_id">User *</label>
                                    <select class="form-control" id="user_id" name="user_id" required>
                                        <option value="">-- Pilih User --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="name">Nama Lengkap *</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>

                                <div class="form-group">
                                    <label for="username">Username *</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>

                                <div class="form-group">
                                    <label for="password">Password *</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>

                                <div class="form-group">
                                    <label for="nomor">No Telepon *</label>
                                    <input type="text" class="form-control" id="nomor" name="nomor" required>
                                </div>

                                <div class="form-group">
                                    <label for="tanggal_lahir">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir">
                                </div>

                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="2"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="alasan_bergabung">Alasan Bergabung</label>
                                    <textarea class="form-control" id="alasan_bergabung" name="alasan_bergabung" rows="3"></textarea>
                                </div>

                                 <div class="form-group">
                                    <label for="role">Role</label>
                                    <select class="form-control" id="role" name="role">
                                        <option value="">-- Pilih Role --</option>
                                        <option value="Petugas">Petugas</option>
                                        </select>
                                </div>

                                <div class="form-group">
                                    <label for="foto_diri">Foto Diri</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="foto_diri" name="foto_diri">
                                            <label class="custom-file-label" for="foto_diri">Pilih Foto</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Rasio 1:1 dan ukuran kurang dari 2MB.</small>
                                </div>

                                <button type="submit" class="btn btn-success">Simpan</button>
                                <a href="{{ route('petugas.index') }}" class="btn btn-secondary ml-2">Batal</a>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .custom-file-label::after {
            content: "Pilih";
        }
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function () {
            bsCustomFileInput.init();
        });
    </script>
@endpush
