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
                    <h5 class="m-0">Tambah Data Perhitungan Sampah (Admin Pusat)</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pusat.perhitungan-sampah.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="tanggal_pengangkutan">Tanggal Pengangkutan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pengangkutan" id="tanggal_pengangkutan" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="total_sampah">Berat Sampah (kg) <span class="text-danger">*</span></label>
                            <input type="number" name="total_sampah" id="total_sampah" class="form-control" placeholder="Masukkan berat sampah (kg)" required>
                        </div>

                        <div class="form-group row mt-4">
                            <div class="col-md-4 d-flex justify-content-start" style="gap: 10px;">
                                <button type="submit" class="btn btn-success">Simpan</button>
                                <a href="{{ route('pusat.perhitungan-sampah.index') }}" class="btn btn-danger" style="width: 80px;">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
