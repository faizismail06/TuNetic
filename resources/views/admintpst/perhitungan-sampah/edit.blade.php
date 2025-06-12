@extends('layouts.app')

@section('content')

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="content">
        <div class="container-fluid mt-4">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h5 class="m-0">Edit Data Perhitungan Sampah</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('perhitungan-sampah.update', $data->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- pakai PUT untuk update --}}

                        <div class="form-group">
                            <label for="tanggal_pengangkutan">Tanggal Pengangkutan <span
                                    class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pengangkutan" id="tanggal_pengangkutan" class="form-control"
                                value="{{ old('tanggal_pengangkutan', \Carbon\Carbon::parse($data->tanggal_pengangkutan)->format('Y-m-d')) }}"
                                readonly onfocus="this.blur()">
                        </div>

                        <div class="form-group">
                            <label for="id_armada">Armada <span class="text-danger">*</span></label>
                            <select name="id_armada" class="form-control" disabled>
                                @foreach ($armadas as $armada)
                                    <option value="{{ $armada->id }}" {{ $data->jadwalOperasional->id_armada == $armada->id ? 'selected' : '' }}>
                                        {{ $armada->no_polisi }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="id_armada" value="{{ $data->jadwalOperasional->id_armada }}">
                        </div>

                        <div class="form-group">
                            <label for="id_rute">Rute <span class="text-danger">*</span></label>
                            <select name="id_rute" class="form-control" disabled>
                                @foreach ($rutes as $rute)
                                    <option value="{{ $rute->id }}" {{ $data->jadwalOperasional->id_rute == $rute->id ? 'selected' : '' }}>
                                        {{ $rute->nama_rute }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="id_rute" value="{{ $data->jadwalOperasional->id_rute }}">
                        </div>

                        <div class="form-group">
                            <label for="total_sampah">Berat Sampah (kg) <span class="text-danger">*</span></label>
                            <input type="number" name="total_sampah" id="berat" class="form-control"
                                placeholder="Masukkan berat sampah (kg)"
                                value="{{ old('total_sampah', $data->total_sampah) }}" required>
                        </div>

                        <div class="form-group row mt-4">
                            <div class="col-md-4 d-flex justify-content-start" style="gap: 10px;">
                                <button type="submit" class="btn btn-success">Update</button>
                                <a href="{{ route('perhitungan-sampah.index') }}" class="btn btn-danger"
                                    style="width: 80px;">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection