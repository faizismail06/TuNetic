@extends('layouts.app')
@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('') }}plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('') }}plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('') }}plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endpush
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 text-uppercase">
                    <h4 class="m-0">kelola tps/tpst</h4>
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
                            <h3 class="card-title">Data TPS/TPST</h3>
                            <div class="card-tools">
                                <a href="{{ route('lokasi-tps.create') }}" class="btn btn-tool"><i
                                        class="fas fa-plus-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="datatable-main" class="table table-bordered table-striped">
                                <thead>
                                    <th>ID</th>
                                    <th>Nama TPS/TPST</th>
                                    <th>Provinsi</th>
                                    <th>Kabupaten</th>
                                    <th>Kecamatan</th>
                                    <th>Desa</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Aksi</th>
                                </thead>
                                <tbody>
                                    @foreach ($lokasi as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama_lokasi }}</td>
                                            <td>{{ $item->province->name ?? '-'}}</td>
                                            <td>{{ $item->regency->name }}</td>
                                            <td>{{ $item->district->name }}</td>
                                            <td>{{ $item->village->name }}</td>
                                            <td>{{ $item->latitude }}</td>
                                            <td>{{ $item->longitude }}</td>
                                            <td>
                                                <button type="button" class="btn btn-block btn-sm btn-outline-info"
                                                    data-toggle="dropdown"><i class="fas fa-cog"></i>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('lokasi-tps.edit', $item->id) }}">Edit</a>
                                                    <a class="dropdown-item" href="#">Hapus</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection