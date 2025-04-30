@extends('layouts.app')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 text-uppercase">
                <h4 class="m-0">Jadwal Operasional</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    {{-- Breadcrumb opsional --}}
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
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="m-0">Data Jadwal Operasional</h5>
                        <a href="{{ route('jadwal-operasional.create') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <table id="datatable-main" class="table table-bordered table-striped text-sm">
                            <thead class="text-center">
                                <tr>
                                    <th>ID</th>
                                    <th>Tanggal</th>
                                    <th>Armada</th>
                                    <th>Hari</th>
                                    <th>Rute</th>
                                    <th>Jam</th>
                                    <th>Petugas</th>
                                    <th>Tugas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jadwals as $item)
                                    <tr>
                                        <td>{{ $item->kode ?? 'JDO-' . \Carbon\Carbon::parse($item->tanggal)->format('Ymd') . '-' . $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                        <td>{{ $item->armada->no_polisi ?? '-' }}</td>
                                        <td>{{ $item->jadwal->hari }}</td>
                                        <td>{{ $item->ruteTps->rute->nama_rute ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->jam_aktif)->format('H.i') }}</td>
                                        <td>
                                            @if ($item->penugasanPetugas->count())
                                                {{ $item->penugasanPetugas->pluck('petugas.name')->implode(', ') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->penugasanPetugas->count())
                                                @foreach ($item->penugasanPetugas as $tugas)
                                                    <span class="badge badge-info">
                                                        {{ $tugas->tugas }}
                                                    </span>
                                                @endforeach
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-info dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('jadwal-operasional.edit', $item->id) }}">Edit</a>
                                                    <form method="POST" action="{{ route('jadwal-operasional.destroy', $item->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="dropdown-item confirm-button">Hapus</a>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> <!-- /.card-body -->
                </div> <!-- /.card -->
            </div>
        </div>
    </div>
</div>
@endsection

