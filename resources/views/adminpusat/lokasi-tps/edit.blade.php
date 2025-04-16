@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <h4 class="text-uppercase">Edit Lokasi TPS</h4>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card card-outline card-warning">
                <div class="card-body">
                    <form action="{{ route('tps.update', $tps->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Nama TPS</label>
                            <input type="text" name="nama" class="form-control" value="{{ $tps->nama }}" required>
                        </div>

                        <div class="form-group">
                            <label>Latitude</label>
                            <input type="text" name="latitude" class="form-control" value="{{ $tps->latitude }}" required>
                        </div>

                        <div class="form-group">
                            <label>Longitude</label>
                            <input type="text" name="longitude" class="form-control" value="{{ $tps->longitude }}" required>
                        </div>

                        <button type="submit" class="btn btn-info">Update</button>
                        <a href="{{ route('tps.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
