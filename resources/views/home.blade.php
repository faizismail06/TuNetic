@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4 class="m-0">Selamat datang {{ ucwords(auth()->user()->name) }}</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    </ol>
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
                            <h5 class="m-0">Judul Section</h5>
                        </div>
                        <div class="card-body">
                            Silakan tambahkan konten aplikasi disini.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $('.toast').toast('show')
    </script>
@endpush
