@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h4 class="mb-2 text-uppercase">Generate Jadwal Otomatis</h4>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card card-success card-outline">
            <div class="card-body">
                <form action="{{ route('daftar-jadwal.generate.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Bulan Mulai</label>
                        <input type="month" name="bulan_mulai" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Bulan Akhir</label>
                        <input type="month" name="bulan_akhir" class="form-control" required>
                    </div>

                    <div class="text-right">
                        <a href="{{ route('daftar-jadwal.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-success">Generate Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
