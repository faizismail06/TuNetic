@extends('layouts.app')

@section('content')
<div class="content">
    <div class="container-fluid mt-4">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h5 class="m-0">Generate Jadwal Operasional</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('daftar-jadwal.generate.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="bulan_mulai">Bulan Mulai</label>
                        <input type="month" name="bulan_mulai" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="bulan_akhir">Bulan Akhir</label>
                        <input type="month" name="bulan_akhir" class="form-control" required>
                    </div>

                    <div class="form-group text-right">
                        <a href="{{ route('daftar-jadwal.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-success">Generate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
