@extends('layouts.app')

@push('css')
    <style>
        .form-inline .form-group {
            margin-right: 15px;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <h4 class="text-uppercase">{{ isset($template) ? 'Edit' : 'Tambah' }} Jadwal Template</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ isset($template) ? route('jadwal-template.update', $template->id) : route('jadwal-template.store') }}">
        @csrf
        @if(isset($template))
            @method('PUT')
        @endif

        <div class="card card-outline card-success mt-3">
            <div class="card-body">
                <div class="form-group">
                    <label>Hari</label>
                    <select name="hari" class="form-control" required>
                        @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h)
                            <option value="{{ $h }}" {{ old('hari', $template->hari ?? request('hari')) == $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Armada</label>
                    <select name="id_armada" class="form-control" required>
                        @foreach ($armadas as $a)
                            <option value="{{ $a->id }}" {{ old('id_armada', $template->id_armada ?? '') == $a->id ? 'selected' : '' }}>
                                {{ $a->jenis_kendaraan }} - {{ $a->no_polisi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Rute</label>
                    <select name="id_rute" class="form-control" required>
                        @foreach ($rutes as $r)
                            <option value="{{ $r->id }}" {{ old('id_rute', $template->id_rute ?? '') == $r->id ? 'selected' : '' }}>
                                {{ $r->nama_rute }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr>
                <h6>Petugas</h6>
                <div id="petugas-wrapper">
                    @php
                        $ptList = old('petugas', $template->petugasTemplate ?? []);
                    @endphp

                    @foreach ($ptList as $i => $pt)
                    <div class="form-inline mb-2">
                        <select name="petugas[{{ $i }}][id_petugas]" class="form-control" required>
                            <option value="">-- Pilih Petugas --</option>
                            @foreach ($petugas as $p)
                                <option value="{{ $p->id }}"
                                    {{ (is_object($pt) ? $pt->id_petugas : $pt['id_petugas'] ?? '') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                        <select name="petugas[{{ $i }}][tugas]" class="form-control ml-2" required>
                            <option value="1" {{ (is_object($pt) ? $pt->tugas : $pt['tugas'] ?? '') == 1 ? 'selected' : '' }}>Driver</option>
                            <option value="2" {{ (is_object($pt) ? $pt->tugas : $pt['tugas'] ?? '') == 2 ? 'selected' : '' }}>Picker</option>
                        </select>
                        <button type="button" class="btn btn-danger btn-sm ml-2" onclick="this.parentElement.remove()">Hapus</button>
                    </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-sm btn-outline-primary" onclick="tambahPetugas()">+ Tambah Petugas</button>

                <div class="form-group mt-3 text-right">
                    <a href="{{ route('jadwal-template.index', ['hari' => old('hari', $template->hari ?? request('hari'))]) }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@push('js')
<script>
    let index = {{ count(old('petugas', $template->petugasTemplate ?? [])) }};
    function tambahPetugas() {
        let html = `
        <div class="form-inline mb-2">
            <select name="petugas[${index}][id_petugas]" class="form-control" required>
                <option value="">-- Pilih Petugas --</option>
                @foreach ($petugas as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
            <select name="petugas[${index}][tugas]" class="form-control ml-2" required>
                <option value="1">Driver</option>
                <option value="2">Picker</option>
            </select>
            <button type="button" class="btn btn-danger btn-sm ml-2" onclick="this.parentElement.remove()">Hapus</button>
        </div>
        `;
        document.getElementById('petugas-wrapper').insertAdjacentHTML('beforeend', html);
        index++;
    }
</script>
@endpush
