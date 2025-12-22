@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 text-uppercase">
                <h4 class="m-0">Tambah TPS/TPST</h4>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <div class="card-tools">
                            <a href="{{ route('lokasi-tps.index') }}" class="btn btn-tool"><i class="fas fa-arrow-alt-circle-left"></i></a>
                        </div>
                    </div>
                    <form action="{{ route('lokasi-tps.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nama TPS/TPST <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lokasi" class="form-control @error('nama_lokasi') is-invalid @enderror" placeholder="Masukkan nama TPS" value="{{ old('nama_lokasi') }}">
                                @error('nama_lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Provinsi</label>
                                    <select name="province_id" class="form-control @error('province_id') is-invalid @enderror">
                                        <option value="">Pilih Provinsi</option>
                                        @foreach($provinces as $prov)
                                            <option value="{{ $prov->id }}" {{ old('province_id') == $prov->id ? 'selected' : '' }}>{{ $prov->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('province_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Kabupaten/Kota</label>
                                    <select name="regency_id" class="form-control @error('regency_id') is-invalid @enderror">
                                        <option value="">Pilih Kabupaten/Kota</option>
                                        {{-- Data kabupaten ditampilkan dinamis via JS --}}
                                    </select>
                                    @error('regency_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Kecamatan</label>
                                    <select name="district_id" class="form-control @error('district_id') is-invalid @enderror">
                                        <option value="">Pilih Kecamatan</option>
                                        {{-- Data kecamatan ditampilkan dinamis via JS --}}
                                    </select>
                                    @error('district_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Desa/Kelurahan</label>
                                    <select name="village_id" class="form-control @error('village_id') is-invalid @enderror">
                                        <option value="">Pilih Desa/Kelurahan</option>
                                        {{-- Data desa ditampilkan dinamis via JS --}}
                                    </select>
                                    @error('village_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Latitude</label>
                                    <input type="text" name="latitude" class="form-control @error('latitude') is-invalid @enderror" placeholder="Masukkan koordinat" value="{{ old('latitude') }}">
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Longitude</label>
                                    <input type="text" name="longitude" class="form-control @error('longitude') is-invalid @enderror" placeholder="Masukkan koordinat" value="{{ old('longitude') }}">
                                    @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success btn-block">Tambahkan TPS</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
