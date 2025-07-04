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
                    <h4 class="m-0">master data</h4>
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
                            <h5 class="m-0"></h5>
                            <div class="card-tools">
                                <a href="{{ route('manage-menu.create') }}" class="btn btn-tool"><i
                                        class="fas fa-plus-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="datatable-main" class="table table-bordered table-striped text-sm">
                                <thead>
                                    <th>No</th>
                                    <th>Menu</th>
                                    <th>URL</th>
                                    <th>Icon</th>
                                    <th>Parent</th>
                                    <th>Permission</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach ($menus as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama_menu }}</td>
                                            <td>{{ $item->url }}</td>
                                            <td>{{ $item->icon ? $item->icon : '-' }}</td>
                                            <td>{{ $item->parent ? $item->parent->nama_menu : '-' }}</td>
                                            <td>
                                                @if (count($item->permissions) < 1)
                                                    {!! '-' !!}
                                                @else
                                                    @foreach ($item->permissions as $permission)
                                                        <form method="POST"
                                                            action="{{ route('manage-permission.destroy', $permission->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            {{ $permission->name }} <a class="text-danger confirm-button" href="#">x</a>

                                                        </form>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-block btn-sm btn-outline-info"
                                                    data-toggle="dropdown"><i class="fas fa-cog"></i>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('manage-menu.edit', $item->id) }}">Edit</a>
                                                    <form method="POST" action="{{ route('manage-menu.destroy', $item->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a class="dropdown-item confirm-button" href="#">Hapus</a>
                                                    </form>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" data-toggle="modal"
                                                        data-target="#modal-default{{ $item->id }}" href="#">Tambah
                                                        Permission</a>

                                            </td>
                                        </tr>
                                        <div class="modal fade" id="modal-default{{ $item->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Permissions {{ $item->nama_menu }} </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('manage-permission.store') }}" method="post">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <input type="text" name="menu_id" value="{{ $item->id }}" hidden>
                                                            <input type="text" name="permission" value="" class="form-control"
                                                                placeholder="Permission">

                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default btn-flat btn-sm"
                                                                data-dismiss="modal">Batal</button>
                                                            <button type="submit"
                                                                class="btn btn-flat btn-sm btn-info">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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