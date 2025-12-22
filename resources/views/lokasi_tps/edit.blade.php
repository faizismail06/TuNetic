@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 text-uppercase">
                <h4 class="m-0">Edit TPS/TPST</h4>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <div class="card-tools">
                            <a href="{{ route('lokasi-tps.index') }}" class="btn btn-tool"><i class="fas fa-arrow-alt-circle-left"></i></a>
                        </div>
                    </div>
                    <form action="{{ route('lokasi-tps.update', $lokasi->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nama TPS/TPST <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lokasi" class="form-control @error('nama_lokasi') is-invalid @enderror" value="{{ old('nama_lokasi', $lokasi->nama_lokasi) }}">
                                @error('nama_lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        <div class="form-row">
                                <div class="form-group col-md-6">
                                <label>Provinsi</label>
                                <select name="province_id" class="form-control @error('province_id') is-invalid @enderror">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}" {{ $province->id == $lokasi->province_id ? 'selected' : '' }}>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('province_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                </div>

                            <div class="form-group col-md-6">
                                <label>Kabupaten/Kota</label>
                                <select name="regency_id" class="form-control @error('regency_id') is-invalid @enderror">
                                    <option value="">-- Pilih Kabupaten/Kota --</option>
                                    @foreach ($regencies as $regency)
                                        <option value="{{ $regency->id }}" {{ $regency->id == $lokasi->regency_id ? 'selected' : '' }}>
                                            {{ $regency->name }}
                                        </option>
                                    @endforeach
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
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}" {{ $district->id == $lokasi->district_id ? 'selected' : '' }}>
                                            {{ $district->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('district_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Desa/Kelurahan</label>
                                <select name="village_id" class="form-control @error('village_id') is-invalid @enderror">
                                    <option value="">-- Pilih Desa --</option>
                                    @foreach ($villages as $village)
                                        <option value="{{ $village->id }}" {{ $village->id == $lokasi->village_id ? 'selected' : '' }}>
                                            {{ $village->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('village_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Latitude</label>
                                    <input type="text" name="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude', $lokasi->latitude) }}">
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Longitude</label>
                                    <input type="text" name="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude', $lokasi->longitude) }}">
                                    @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-info btn-block">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
