@extends('layouts.app')

@push('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gap-2>* {
            margin-right: 0.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="content">
        <div class="container-fluid mt-4">
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

            <div class="card card-success card-outline">
                <div class="card-header">
                    <div class="d-flex justify-content-between flex-wrap align-items-center">
                        <h5 class="m-0">Perhitungan Sampah</h5>
                        <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                            <a href="{{ route('perhitungan-sampah.create') }}" class="btn btn-sm btn-success">
                                <i class="fas fa-plus-circle me-2"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Filter tanggal --}}
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="date" id="start-date" class="form-control" placeholder="Dari Tanggal">
                        </div>
                        <div class="col-md-3">
                            <input type="date" id="end-date" class="form-control" placeholder="Sampai Tanggal">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-sm btn-success" id="filter-date">Filter</button>
                            <button class="btn btn-sm btn-warning" id="reset-date" style="color: white;">Reset</button>
                        </div>
                    </div>

                    <table id="datatable-main" class="table table-bordered table-striped text-sm">
                        <thead class="text-center">
                            <tr>
                                <th>ID</th>
                                <th>Armada</th>
                                <th>Tanggal Pengangkutan</th>
                                <th>Rute</th>
                                <th>Sampah (Kg)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($LaporanTps as $item)
                                <tr>
                                    <td class="text-center">{{ $item->id }}</td>
                                    <td class="text-center">{{ $item->jadwalOperasional->armada->no_polisi ?? '-' }}</td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($item->tanggal_pengangkutan)->format('d M Y') }}
                                    </td>
                                    <td class="text-center">{{ $item->jadwalOperasional->rute->nama_rute ?? '-' }}</td>
                                    <td class="text-center">{{ number_format($item->total_sampah, 2, '.', '') }}</td>
                                    <td class="text-center gap-2 d-flex justify-content-center">
                                        <a href="{{ route('perhitungan-sampah.edit', $item->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit me-2"></i> Edit
                                        </a>
                                        <form action="{{ route('perhitungan-sampah.destroy', $item->id) }}" method="POST"
                                            class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger show_confirm"
                                                data-name="{{ $item->id }}">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="text-right">
                            <tr>
                                <th colspan="4" class="text-center">TOTAL</th>
                                <th id="total-berat" class="text-center"></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables core CSS -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <!-- DataTables core JS -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- DataTables Buttons JS -->
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

    <!-- Buttons dependencies jika pakai export (Excel, PDF, print) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <script>
        $(function () {
            // Inisialisasi filter tanggal kosong
            var start = '';
            var end = '';

            // Tambahkan custom filter DataTables untuk filter tanggal
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                var tanggal = data[2]; // kolom tanggal (format: DD MMM YYYY)
                if (!tanggal) return true;

                var parsedDate = moment(tanggal, 'DD MMM YYYY', true);
                if (!parsedDate.isValid()) {
                    return true; // Jangan filter baris jika tanggal invalid
                }
                var dateFormatted = parsedDate.format('YYYY-MM-DD');

                if ((start === '' || dateFormatted >= start) && (end === '' || dateFormatted <= end)) {
                    return true;
                }
                return false;
            });

            // Inisialisasi DataTable
            var table = $('#datatable-main').DataTable({
                responsive: true,
                autoWidth: false,
                destroy: true,
                footerCallback: function (row, data, startIdx, end, display) {
                    var api = this.api();

                    // Fungsi parsing angka
                    var intVal = function (i) {
                        if (typeof i === 'string') {
                            return parseFloat(i.replace(/,/g, '')) || 0;
                        } else if (typeof i === 'number') {
                            return i;
                        }
                        return 0;
                    };

                    // Total seluruh data yang terfilter (kolom index 4)
                    var total = 0;
                    for (var i = 0; i < display.length; i++) {
                        total += intVal(api.row(display[i]).data()[4]);
                    }

                    var totalFormatted = total.toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    $(api.column(4).footer()).html(totalFormatted + ' Kg');
                }
            });

            // Event tombol filter
            $('#filter-date').on('click', function () {
                start = $('#start-date').val();
                end = $('#end-date').val();
                table.draw();
            });

            // Event tombol reset
            $('#reset-date').on('click', function () {
                $('#start-date').val('');
                $('#end-date').val('');
                start = '';
                end = '';
                table.draw();
            });
        });

        // Konfirmasi saat klik tombol hapus
        $(document).on('click', '.show_confirm', function (event) {
            event.preventDefault();

            var form = $(this).closest("form");
            var dataName = $(this).attr("data-name");

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data dengan ID " + dataName + " akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-success' // Hijau
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush