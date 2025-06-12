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
            <div class="card card-success card-outline">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="m-0 p-1">Data Jadwal Operasional</h5>
                    <div class="ml-auto">
                        <a href="{{ route('jadwal-operasional.create') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle"></i> Tambah Jadwal
                        </a>
                    </div>
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
                                <th>Penugasan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jadwals as $item)
                                <tr>
                                    <td>{{ $item->kode ?? 'JDO-' . \Carbon\Carbon::parse($item->tanggal)->format('Ymd') . '-' . $loop->iteration }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                    <td>{{ $item->armada->no_polisi ?? '-' }}</td>
                                    <td>{{ $item->jadwal->hari }}</td>
                                    <td>{{ $item->rute->nama_rute ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->jam_aktif)->format('H.i') }}</td>
                                    {{-- <td>
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
                                </td> --}}
                                    <td>
                                        @if ($item->penugasanPetugas->count())
                                            <ul class="pl-3">
                                                @foreach ($item->penugasanPetugas as $penugasan)
                                                    <li>
                                                        <strong>{{ $penugasan->petugas->name }}</strong> —
                                                        {{ $penugasan->tugas }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">Belum Ada</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-info dropdown-toggle"
                                                data-toggle="dropdown">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item btn-plotting" href="#"
                                                    data-id="{{ $item->id }}"
                                                    data-penugasan='@json($item->penugasanPetugas)'>Plotting Petugas</a>
                                                <a class="dropdown-item"
                                                    href="{{ route('jadwal-operasional.edit', $item->id) }}">Edit</a>
                                                <form method="POST"
                                                    action="{{ route('jadwal-operasional.destroy', $item->id) }}">
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
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Modal Plotting Petugas -->
<div class="modal fade" id="modalPlotting" tabindex="-1" role="dialog" aria-labelledby="modalPlottingLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="formPlotting">
            @csrf
            <input type="hidden" name="jadwal_id" id="jadwal_id">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="modalPlottingLabel">Plotting Petugas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="containerPlotting"></div>
                    <button type="button" id="btnAddRow" class="btn btn-sm btn-outline-primary mt-2">+ Tambah
                        Petugas</button>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const semuaPetugas = @json($semuaPetugas); // kirim dari controller

    function generateRow(petugasId = '', tugas = '') {
        let options = semuaPetugas.map(p =>
            `<option value="${p.id}" ${p.id == petugasId ? 'selected' : ''}>${p.name}</option>`
        ).join('');
        return `
            <div class="form-row plotting-row mb-2">
                <div class="col-md-5">
                    <select name="petugas_ids[]" class="form-control" required>
                        <option value="">Pilih Petugas</option>
                        ${options}
                    </select>
                </div>
                <div class="col-md-5">
                    <select name="tugas[]" class="form-control" required>
                        <option value="1">Driver</option>
                        <option value="2">Picker</option>
                        ${tugas}
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm btn-remove-row">Hapus</button>
                </div>
            </div>
        `;
    }

    $(document).on('click', '.btn-plotting', function () {
        let penugasan = $(this).data('penugasan');
        let id = $(this).data('id');

        $('#jadwal_id').val(id);
        $('#containerPlotting').html('');

        if (penugasan.length > 0) {
            penugasan.forEach(p => {
                $('#containerPlotting').append(generateRow(p.petugas_id, p.tugas));
            });
        } else {
            $('#containerPlotting').append(generateRow());
        }

        $('#modalPlotting').modal('show');
    });

    $('#btnAddRow').click(function () {
        $('#containerPlotting').append(generateRow());
    });

    $(document).on('click', '.btn-remove-row', function () {
        $(this).closest('.plotting-row').remove();
    });

    $('#formPlotting').submit(function (e) {
        e.preventDefault();
        let formData = $(this).serialize();

    $.post("{{ url('pusat/jadwal-operasional') }}/" + $('#jadwal_id').val() + "/plotting", formData, function (response) {
            $('#modalPlotting').modal('hide');
            location.reload(); // atau refresh sebagian pakai AJAX
        }).fail(function (xhr) {
            alert("Gagal menyimpan data.");
        });
    });
</script>
@endpush
