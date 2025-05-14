@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="text-uppercase">Edit Jadwal Template</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('jadwal-template.update', $template->id) }}">
        @csrf
        @method('PUT')

        <div class="card card-outline card-success mt-3">
            <div class="card-body">
                {{-- Hari --}}
                <div class="form-group">
                    <label>Hari</label>
                    <select name="hari" class="form-control" required>
                        @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h)
                            <option value="{{ $h }}" {{ old('hari', $template->hari) == $h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Armada --}}
                <div class="form-group">
                    <label>Armada</label>
                    <select name="id_armada" class="form-control" required>
                        @foreach ($armadas as $a)
                            <option value="{{ $a->id }}" {{ old('id_armada', $template->id_armada) == $a->id ? 'selected' : '' }}>
                                {{ $a->jenis_kendaraan }} - {{ $a->no_polisi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Rute --}}
                <div class="form-group">
                    <label>Rute</label>
                    <select name="id_rute" class="form-control" required>
                        @foreach ($rutes as $r)
                            <option value="{{ $r->id }}" {{ old('id_rute', $template->id_rute) == $r->id ? 'selected' : '' }}>
                                {{ $r->nama_rute }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr>
                <h6>Petugas</h6>
                <div id="petugas-wrapper">
                    @foreach ($template->petugasTemplate as $i => $pt)
                    <div class="form-inline mb-2" id="petugasRow-{{ $i }}">
                        <select name="petugas[{{ $i }}][id_petugas]" class="form-control mr-2" required>
                            <option value="">-- Pilih Petugas --</option>
                            @foreach ($petugas as $p)
                                <option value="{{ $p->id }}" {{ $pt->id_petugas == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="petugas[{{ $i }}][tugas]" class="form-control mr-2" required>
                            <option value="1" {{ $pt->tugas == 1 ? 'selected' : '' }}>Driver</option>
                            <option value="2" {{ $pt->tugas == 2 ? 'selected' : '' }}>Picker</option>
                        </select>

                        <button type="button" class="btn btn-danger btn-sm" onclick="hapusPetugas({{ $i }})">Hapus</button>
                    </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="tambahPetugas()">+ Tambah Petugas</button>

                <div class="form-group mt-4 text-right">
                    <a href="{{ route('jadwal-template.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('js')
<script>
    let petugasIndex = {{ count($template->petugasTemplate) }};

    function tambahPetugas() {
        const wrapper = document.getElementById('petugas-wrapper');
        let html = `
        <div class="form-inline mb-2" id="petugasRow-${petugasIndex}">
            <select name="petugas[${petugasIndex}][id_petugas]" class="form-control mr-2" required>
                <option value="">-- Pilih Petugas --</option>
                @foreach ($petugas as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
            <select name="petugas[${petugasIndex}][tugas]" class="form-control mr-2" required>
                <option value="1">Driver</option>
                <option value="2">Picker</option>
            </select>
            <button type="button" class="btn btn-danger btn-sm" onclick="hapusPetugas(${petugasIndex})">Hapus</button>
        </div>`;
        wrapper.insertAdjacentHTML('beforeend', html);
        petugasIndex++;
    }

    function hapusPetugas(index) {
        const row = document.getElementById('petugasRow-' + index);
        if (row) row.remove();
    }
</script>
@endpush
