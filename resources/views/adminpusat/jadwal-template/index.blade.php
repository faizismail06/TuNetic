@extends('layouts.app')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid mt-4">
        <div class="card card-success card-outline">
            <div class="card-header d-flex justify-content-between">
                <h5 class="m-0 p-2">Template Jadwal</h5>
                <select id="hariSelector" class="ml-auto align-self-center form-control w-25 mr-2">
                    @foreach ($hariList as $hari)
                    <option value="{{ $hari }}">{{ $hari }}</option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#tambahTemplateModal">
                    <i class="fas fa-plus-circle"></i> Tambah Template
                </button>
            </div>


            <div class="card-body">
                <div id="templateContainer">
                    <!-- Data template akan dimuat via AJAX -->
                    <p class="text-muted">Silakan pilih hari untuk melihat template.</p>
                </div>
            </div>
        </div>


        <!-- Modal Tambah Template -->
        <div class="modal fade" id="tambahTemplateModal" tabindex="-1" aria-labelledby="tambahTemplateModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form id="formTambahTemplate">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahTemplateModalLabel">Tambah Jadwal Template</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="hari" id="hariInput">

                            <div class="form-group">
                                <label for="id_armada">Armada</label>
                                <select name="id_armada" id="id_armada" class="form-control" required>
                                    @foreach (\App\Models\Armada::all() as $armada)
                                        <option value="{{ $armada->id }}">{{ $armada->jenis_kendaraan }} -
                                            {{ $armada->no_polisi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="id_rute">Rute</label>
                                <select name="id_rute" id="id_rute" class="form-control" required>
                                    @foreach (\App\Models\Rute::all() as $rute)
                                        <option value="{{ $rute->id }}">{{ $rute->nama_rute }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Petugas</label>
                                <div id="petugas-container">
                                    @foreach (\App\Models\Petugas::all() as $petugas)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="petugas[]"
                                                value="{{ $petugas->id }}" data-nama="{{ $petugas->name }}">
                                            <label class="form-check-label">{{ $petugas->name }}</label>
                                            <select class="form-control form-control-sm d-inline w-auto ml-2"
                                                name="tugas[{{ $petugas->id }}]">
                                                <option value="1">Driver</option>
                                                <option value="2">Picker</option>
                                            </select>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Simpan</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Trigger awal
            const defaultHari = $('#hariSelector').val();
            loadTemplates(defaultHari);

            // Jika hari diubah
            $('#hariSelector').on('change', function() {
                const selectedDay = $(this).val();
                loadTemplates(selectedDay);
            });

            function loadTemplates(hari) {
                $('#templateContainer').html('<p>Loading...</p>');
                $.ajax({
                    url: `/jadwal-template/filter/${hari}`,
                    method: 'GET',
                    success: function(templates) {
                        if (templates.length === 0) {
                            $('#templateContainer').html(
                                '<p class="text-muted">Belum ada template untuk hari ini.</p>');
                            return;
                        }

                        let html = `<table id="datatable-main" class="table table-bordered table-striped text-sm">
                        <thead class="text-center">
                            <tr>
                                <th>Armada</th>
                                <th>Rute</th>
                                <th>Petugas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>`;

                        templates.forEach(template => {
                            html += `
                        <tr>
                            <td>${template.armada?.jenis_kendaraan ?? '-'} - ${template.armada?.no_polisi ?? '-'}</td>
                            <td>${template.rute?.nama_rute ?? '-'}</td>
                            <td>
                                ${template.petugas_template.map(p => `${p.petugas?.name ?? 'Petugas'} (${p.tugas == 1 ? 'Driver' : 'Picker'})`).join('<br>')}
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-info dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="/jadwal-template/${template.id}/edit">Edit</a>
                                        <form action="/jadwal-template/${template.id}" method="POST" onsubmit="return confirm('Yakin hapus template ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item confirm-button">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>`;
                        });

                        html += `</tbody></table>`;
                        $('#templateContainer').html(html);
                    },
                    error: function() {
                        $('#templateContainer').html('<p class="text-danger">Gagal memuat data.</p>');
                    }
                });
            }
        });

        // Saat modal dibuka, isi input hari dengan hari terpilih
        $('#tambahTemplateModal').on('show.bs.modal', function() {
            const hari = $('#hariSelector').val();
            $('#hariInput').val(hari);
        });

    $('#formTambahTemplate').on('submit', function(e) {
        e.preventDefault();

        const hari = $('#hariInput').val();
        const id_armada = $('#id_armada').val();
        const id_rute = $('#id_rute').val();
        const petugasSelected = [];

        $('#petugas-container input[type="checkbox"]:checked').each(function() {
            const id = $(this).val();
            const tugas = $(`select[name="tugas[${id}]"]`).val();
            if (id && tugas) {
                petugasSelected.push({
                    id_petugas: id,
                    tugas: tugas
                });
            }
        });

        if (petugasSelected.length === 0) {
            alert('Pilih minimal 1 petugas.');
            return;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('hari', hari);
        formData.append('id_armada', id_armada);
        formData.append('id_rute', id_rute);

        petugasSelected.forEach((petugas, index) => {
            formData.append(`petugas[${index}][id_petugas]`, petugas.id_petugas);
            formData.append(`petugas[${index}][tugas]`, petugas.tugas);
        });

        $.ajax({
            url: '{{ route("jadwal-template.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                $('#tambahTemplateModal').modal('hide');
                alert(res.message);
                loadTemplates(hari); // reload list
            },
            error: function(xhr) {
                const msg = xhr.responseJSON?.message ?? 'Terjadi kesalahan saat menyimpan.';
                alert(msg);
                console.error(xhr.responseJSON ?? xhr);
            }
        });
    });
    </script>
@endpush
