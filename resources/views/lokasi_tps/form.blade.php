@php
$lokasi = $lokasi ?? null;
@endphp

<div class="mb-3">
    <label class="form-label">Nama Lokasi</label>
    <input type="text" name="nama_lokasi" class="form-control" value="{{ old('nama_lokasi', $lokasi->nama_lokasi ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Latitude</label>
    <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $lokasi->latitude ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Longitude</label>
    <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $lokasi->longitude ?? '') }}">
</div>

{{-- TODO: Tambahkan dropdown provinsi, kabupaten, kecamatan, desa jika sudah ada datanya --}}
