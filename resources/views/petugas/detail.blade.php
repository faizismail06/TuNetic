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
                                        <img src="{{ asset('storage/' . $petugas->sim_image) }}" alt="SIM Petugas" class="img-thumbnail" style="max-width: 250px;">
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
                                <div class="col-12">
                                    <button type="button" class="btn btn-success">Setujui</button>
                                    <button type="button" class="btn btn-danger ml-2">Tolak</button>
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