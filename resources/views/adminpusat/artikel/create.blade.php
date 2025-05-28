@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h5 class="m-0">Tambah Artikel Baru</h5>
            </div>
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('artikel.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Judul Artikel <span style="color: red;">*</span></label>
                        <input type="text" name="judul_artikel" required value="{{ old('judul') }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Tanggal Publikasi <span style="color: red;">*</span></label>
                        <input type="date" name="tanggal_publikasi" required value="{{ old('tanggal') }}"
                            class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Deskripsi Singkat</label>
                        <textarea name="deskripsi_singkat" rows="3" class="form-control">{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="gambar"
                            style="font-size: 1rem; margin-top: 20px; font-weight: 550; display: block;">Thumbnail</label>

                        <!-- Kotak upload -->
                        <div id="upload-box"
                            style="cursor: pointer; border: 2px solid #28a745; padding: 20px; text-align: center; border-radius: 10px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <span id="upload-text">Pilih Gambar</span><br>
                            <img id="preview-image" src="#" alt="Preview Gambar"
                                style="display: none; max-height: 200px; max-width: 100%; object-fit: contain;" />
                        </div>

                        <!-- Input file, HARUS ADA ID -->
                        <input type="file" id="gambar" name="gambar" accept="image/*" style="display: none;">
                    </div>
                    <script>
                        // Trigger input file saat kotak diklik
                        document.getElementById('upload-box').addEventListener('click', function () {
                            document.getElementById('gambar').click();
                        });

                        // Preview saat gambar dipilih
                        document.getElementById('gambar').addEventListener('change', function (event) {
                            const file = event.target.files[0];
                            const preview = document.getElementById('preview-image');
                            const text = document.getElementById('upload-text');

                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function (e) {
                                    preview.src = e.target.result;
                                    preview.style.display = 'block';
                                    text.style.display = 'none';
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    </script>

                    <div class="form-group">
                        <label>Link Artikel <span style="color: red;">*</span></label>
                        <input type="url" name="link_artikel" required value="{{ old('link') }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status') == 'Draf' ? 'selected' : '' }}>Draf</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Upload</button>
                </form>

            </div>
        </div>
    </div>
@endsection