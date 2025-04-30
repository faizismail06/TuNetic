@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 text-uppercase">
                    <h4 class="m-0">Edit Petugas</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <a href="{{ route('petugas.index') }}" class="btn btn-tool"><i class="fas fa-arrow-left"></i></a>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card card-success card-outline">
                <form action="{{ route('petugas.update', $petugas->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        @include('petugas.partials.form', ['petugas' => $petugas])  <!-- Mengirimkan $petugas ke form partial -->
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-info btn-block btn-flat">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
