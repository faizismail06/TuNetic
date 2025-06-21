@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card card-primary">
        <div class="card-header">Edit Data Sampah</div>
        <div class="card-body">
            <form action="{{ route('adminpusat.perhitungan-sampah.update', $laporan->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label for="id_jadwal_operasional" class="form-label">ID Jadwal Operasional</label>
                    <input type="number" class="form-control" name="id_jadwal_operasional" value="{{ $laporan->id_jadwal_operasional }}" required>
                </div>
                <div class="mb-3">
                    <label for="total_sampah" class="form-label">Total Sampah (Kg)</label>
                    <input type="number" class="form-control" name="total_sampah" value="{{ $laporan->total_sampah }}" required min="0">
                </div>
                <div class="mb-3">
                    <label for="tanggal_pengangkutan" class="form-label">Tanggal Pengangkutan</label>
                    <input type="date" class="form-control" name="tanggal_pengangkutan" value="{{ $laporan->tanggal_pengangkutan }}" required>
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi">{{ $laporan->deskripsi }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('adminpusat.perhitungan-sampah.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
