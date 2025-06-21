@extends('layouts.app')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="content">
        <div class="container-fluid mt-4">
            <div class="card card-success card-outline w-100">
                <div class="card-header w-100">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="m-0">Daftar Artikel</h5>
                        <a href="{{ route('artikel.create') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle"></i> Tambah Artikel
                        </a>
                    </div>
                </div>

                <div class="card-body w-100">
                    <div class="table-responsive w-100">
                        <table id="datatable-main" class="table table-bordered table-striped text-sm w-100">
                            <thead class="text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Judul Artikel</th>
                                    <th>Thumbnail</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($artikel as $index => $artikel)
                                    <tr>
                                        <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                        <td class="align-middle text-center">{{ Str::limit($artikel->judul_artikel, 60) }}</td>
                                        <td class="text-center">
                                            <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="Thumbnail"
                                                style="max-height: 80px; max-width: 120px; object-fit: cover;">
                                        </td>
                                        <td class="align-middle text-center">
                                            {{ \Carbon\Carbon::parse($artikel->tanggal)->format('d M Y') }}
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($artikel->status == 1)
                                                <span class="badge badge-success"
                                                    style="font-size: 0.8rem; padding: 0.5em 1em; border-radius: 0.3rem;">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="badge badge-danger"
                                                    style="font-size: 0.8rem; padding: 0.5em 1em; border-radius: 0.3rem;">
                                                    Draft
                                                </span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <a href="{{ route('artikel.edit', $artikel->id) }}"
                                                class="btn btn-sm btn-info mr-1">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $artikel->id }}">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                            <form id="form-delete-{{ $artikel->id }}"
                                                action="{{ route('artikel.destroy', $artikel->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
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
@endsection

@push('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    <script>
        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();
            var id = $(this).data('id');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Artikel akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form-delete-' + id).submit();
                }
            });
        });
    </script>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


@endpush