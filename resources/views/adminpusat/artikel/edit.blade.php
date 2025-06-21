@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h5 class="m-0">Edit Artikel</h5>
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

                <form action="{{ route('artikel.update', $artikel->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Judul Artikel <span style="color: red;">*</span></label>
                        <input type="text" name="judul_artikel" required
                            value="{{ old('judul_artikel', $artikel->judul_artikel) }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Tanggal Publikasi<span style="color: red;">*</span></label>
                        <input type="date" name="tanggal_publikasi" required
                            value="{{ old('tanggal_publikasi', \Carbon\Carbon::parse($artikel->tanggal_publikasi)->format('Y-m-d')) }}"
                            class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Deskripsi Singkat</label>
                        <textarea name="deskripsi_singkat" rows="3"
                            class="form-control">{{ old('deskripsi_singkat', $artikel->deskripsi_singkat) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="gambar" style="font-size: 1rem; font-weight: 550;">Thumbnail</label>

                        <div id="upload-box"
                            style="border: 2px solid #28a745; padding: 20px; text-align: center; border-radius: 10px; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 200px;">
                            <img id="preview-image" src="{{ asset('storage/' . $artikel->gambar) }}" alt="Preview Gambar"
                                style="max-height: 100%; max-width: 100%; object-fit: contain;" />
                        </div>

                        <input type="file" id="gambar" name="gambar" accept="image/*" style="display: none;">
                        <button type="button" id="btn-ganti-gambar" class="btn btn-success mt-2">Ganti Gambar</button>
                    </div>
                    <script>
                        document.getElementById('btn-ganti-gambar').addEventListener('click', function () {
                            document.getElementById('gambar').click();
                        });

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
                        <input type="url" name="link_artikel" required
                            value="{{ old('link_artikel', $artikel->link_artikel) }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ old('status', $artikel->status) == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status', $artikel->status) == 0 ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Update Artikel</button>
                </form>

            </div>
        </div>
    </div>

    <script>
        // Trigger input file saat kotak diklik
        document.getElementById('upload-box').addEventListener('click', function () {
            document.getElementById('gambar').click();
        });

        // Preview gambar setelah dipilih
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
@endsection