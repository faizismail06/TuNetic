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
                        <li class="breadcrumb-item"><a href="{{ route('petugas.index') }}">Data Petugas</a></li>
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
                            <form action="{{ route('petugas.profile.update', $petugas->id) }}" method="POST">
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

                                <div class="form-group mb-3">
                                    <label for="sim_image">SIM Image</label>
                                    <input type="text" class="form-control" name="sim_image" value="{{ $petugas->sim_image ?? '' }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="alasan_bergabung">Alasan Bergabung</label>
                                    <input type="text" class="form-control" name="alasan_bergabung" value="{{ $petugas->alasan_bergabung ?? '' }}">
                                </div>

                                <div class="form-group mb-4">
                                    <label for="role">Role</label>
                                    <input type="text" class="form-control" name="role" value="{{ $petugas->role ?? '' }}">
                                </div>

                                <button type="submit" class="btn btn-primary">Update Petugas</button>
                                <a href="{{ route('petugas.index') }}" class="btn btn-secondary ml-2">Batal</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
