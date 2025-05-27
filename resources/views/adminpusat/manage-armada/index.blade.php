@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 text-uppercase">
                    <h4 class="m-0">Kelola Armada</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right"></ol>
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
                            <h3 class="card-title">Data Armada</h3>
                            <div class="card-tools">
                                <a href="{{ route('manage-armada.create') }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-plus-circle mr-1"></i> Tambah Armada
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="datatable-main" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 1%;" class="text-center">ID</th>
                                        <th class="text-center">Jenis</th>
                                        <th class="text-center">NoPol</th>
                                        <th class="text-center">Merek</th>
                                        <th class="text-center">Kapasitas</th>
                                        {{-- <th>Tanggal</th> --}}
                                        {{-- <th style="width: 30%;" class="text-center">Rute</th> --}}
                                        {{-- <th>Lokasi Laporan</th> --}}
                                        <th style="width: 25%;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($armada as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $item->jenis_kendaraan }}</td>
                                            <td class="text-center">{{ $item->no_polisi }}</td>
                                            <td class="text-center">{{ $item->merk_kendaraan }}</td>
                                            <td class="text-center">{{ $item->kapasitas }}</td>
                                            {{-- <td>{{ $item->tanggal_jadwal }}</td> --}}
                                            {{-- <td>{{ $item->alamat ?? '-' }}</td> --}}
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-outline-info" data-toggle="dropdown" aria-expanded="false" style="width: 150px; height: 36px">
                                                        <i class="fas fa-cog"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                                                        <a class="dropdown-item" href="{{ route('manage-armada.edit', $item->id) }}">Edit</a>
                                                        <div class="dropdown-divider"></div>
                                                        <form action="{{ route('manage-armada.destroy', $item->id) }}" class="form-delete" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="dropdown-item text-danger btn-delete">Hapus</button>
                                                        </form>
                                                    </div>
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

{{-- <div id="modalOverlay" style="display: none; position: fixed; inset: 0; backdrop-filter: blur(6px); background-color: rgba(0, 0, 0, 0.35); z-index: 9999; justify-content: center; align-items: center;">
    <div id="modalContent" style="background: white; padding: 30px 40px; border-radius: 16px; width: 500px; max-width: 95%; box-shadow: 0 10px 25px rgba(0,0,0,0.2); position: relative;">
        <h1 style="text-align: center; font-weight: bold; margin-bottom: 25px; color: #343a40;"> <i class="fas fa-info-circle mr-1"></i> DETAIL RUTE</h1>
        <div id="ruteContent" style="font-size: 20px; line-height: 1.6; color: #444;"></div>
        <button id="closeModalBtn" style="margin-top: 20px; display: block; margin-left: auto; margin-right: auto; background-color: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer;">
            Tutup
        </button>
    </div>
</div> --}}

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        @endif
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('.form-delete');
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data ini tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            // Cek dan hapus inisialisasi sebelumnya jika ada
            if ( $.fn.dataTable.isDataTable(table) ) {
            }

            // Inisialisasi ulang dengan konfigurasi aman
            table.DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 50,
                lengthChange: true,
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    }
                }
            });
        });

        // Tutup jika klik tombol
        document.getElementById('closeModalBtn').addEventListener('click', function () {
            document.getElementById('modalOverlay').style.display = 'none';
        });

        // Tutup jika klik di luar konten
        document.getElementById('modalOverlay').addEventListener('click', function (e) {
            if (!document.getElementById('modalContent').contains(e.target)) {
                this.style.display = 'none';
            }
        });
    </script>
@endpush