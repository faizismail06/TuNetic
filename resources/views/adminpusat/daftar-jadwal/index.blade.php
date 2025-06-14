@extends('layouts.app')

@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

{{-- @push('scripts')
<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

<script>
$(document).ready(function () {
    if (!$.fn.dataTable.isDataTable('#datatable-main')) {
        $("#datatable-main").DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Tidak ada data ditemukan",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data tersedia",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Berikutnya"
                }
            }
        });
    }
});
</script>
@endpush --}}

@section('content')
    <div class="content-header mx-5">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 text-uppercase">
                    <h4 class="m-0">Master Jadwal</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    </ol>
                </div>
            </div>
        </div>
    </div>
<div class="content mx-5">
    <div class="container-fluid">
        <div class="card card-success card-outline">
            <div class="card-header d-flex justify-content-between">
                <h5 class="m-0 p-1">Daftar Jadwal</h5>
                <div class="ml-auto">
                    {{-- <a href="{{ route('daftar-jadwal.generate.form') }}" class="btn btn-sm btn-success mr-2">
                        <i class="fas fa-sync-alt"></i> Generate Jadwal
                    </a> --}}
                    <a href="{{ route('daftar-jadwal.create') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus-circle"></i> Tambah Jadwal
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable-main" class="table table-bordered table-striped text-sm">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Hari</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jadwal as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->hari }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $item->status == 1 ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $item->status == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown">
                                            <i class="fas fa-cogs"></i> Aksi
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{ route('daftar-jadwal.edit', $item->id) }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('daftar-jadwal.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash-alt"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> <!-- table responsive -->
            </div>
        </div>
    </div>
</div>
<div class="content mx-5">
    <div class="container-fluid mt-4">
        <div class="card card-success card-outline">
            <div class="card-header d-flex justify-content-between">
                <h5 class="m-0 p-1">Generate Jadwal Operasional</h5>
                <div class="ml-auto">
                    <a href="/jadwal-template" class="btn btn-sm btn-success">Kelola Template</a>
                </div>
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
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="bulan_mulai">Bulan Mulai</label>
                                <input type="month" name="bulan_mulai" class="form-control" required>
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="bulan_akhir">Bulan Akhir</label>
                                <input type="month" name="bulan_akhir" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Generate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection
