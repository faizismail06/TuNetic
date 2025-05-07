@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card card-success card-outline">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="m-0">Template Jadwal</h5>

                <select id="hariSelector" class="form-control w-25">
                    @foreach ($hariList as $hari)
                        <option value="{{ $hari }}">{{ $hari }}</option>
                    @endforeach
                </select>
            </div>


            <div class="card-body">
                <div id="templateContainer">
                    <!-- Data template akan dimuat via AJAX -->
                    <p class="text-muted">Silakan pilih hari untuk melihat template.</p>
                </div>
            </div>
        </div>
        <!-- Tombol Tambah Template -->
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahTemplateModal">
            Tambah Template
        </button>

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

                        let html = `<table class="table table-bordered table-sm">
                        <thead>
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
                                <form action="/jadwal-template/${template.id}" method="POST" onsubmit="return confirm('Yakin hapus template ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
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

        // Submit form via AJAX
        $('#formTambahTemplate').on('submit', function(e) {
            e.preventDefault();

            // Ambil data
            const hari = $('#hariInput').val();
            const id_armada = $('#id_armada').val();
            const id_rute = $('#id_rute').val();
            const petugasSelected = [];

            $('#petugas-container input[type="checkbox"]:checked').each(function() {
                const id = $(this).val();
                const tugas = $(`select[name="tugas[${id}]"]`).val();
                petugasSelected.push({
                    id_petugas: id,
                    tugas: tugas
                });
            });

            if (petugasSelected.length === 0) {
                alert('Pilih minimal 1 petugas.');
                return;
            }

            $.ajax({
                url: '{{ route('jadwal-template.store') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    hari,
                    id_armada,
                    id_rute,
                    petugas: petugasSelected
                },
                success: function(res) {
                    $('#tambahTemplateModal').modal('hide');
                    alert(res.message);
                    loadTemplates(hari); // refresh list
                },
                error: function(err) {
                    alert('Gagal menyimpan template.');
                    console.error(err);
                }
            });
        });
    </script>
@endpush
