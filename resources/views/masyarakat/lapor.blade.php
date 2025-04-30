@extends('components.navbar')

@section('content')

    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Text:wght@400;500;550;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="main-wrapper">
        <div class="form-wrapper">
            <h2 style="font-size: 1.8rem; margin-bottom: 10px; font-weight: 600">Laporan Sampah Baru</h2>

            <form action="{{ route('lapor.submit') }}" method="POST" enctype="multipart/form-data"
                onsubmit="return validateForm()">

                @csrf

                <input type="hidden" name="id_user" value="{{ auth()->user()->id }}">

                <label for="judul" style="font-size: 1rem; margin-top: 30px; font-weight: 550; display: block;">Judul
                    Laporan</label>
                <input type="text" name="judul" id="judul" placeholder="Masukkan judul laporan" autocomplete="off">

                <label for="gambar" style="font-size: 1rem; margin-top: 20px; font-weight: 550; display: block;">Upload
                    Foto</label>
                <div class="upload-box" onclick="document.getElementById('gambar').click();">
                    <span id="upload-text">Pilih Gambar</span>
                    <img id="preview-image" src="#" alt="Preview Gambar"
                        style="display:none; max-height: 100%; max-width: 100%; object-fit: contain;" />
                </div>
                <input type="file" id="gambar" name="gambar" accept="image/*" style="display:none;">


                <label style="font-size: 1rem; margin-top: 20px; font-weight: 550; display: block;">Tambah Lokasi</label>
                <button type="button" class="location-btn" onclick="getLocation()"><span
                        class="material-icons icon">my_location</span>Lokasi Terkini</button>
                <input type="hidden" name="latitude" id="latitude" required>
                <input type="hidden" name="longitude" id="longitude" required>
                <div id="alamat-wrapper" style="display: none;">
                    <div class="alamat-box">
                        <i class="fas fa-map-marker-alt"></i>
                        <span id="alamat">Alamat akan ditampilkan di sini</span>
                    </div>
                </div>


                <label for="deskripsi"
                    style="font-size: 1rem; margin-top: 20px; font-weight: 550; display: block;">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="7" placeholder="Tulis deskripsi..." required></textarea>

                <input type="hidden" name="status" value="0">

                <div class="buttons">
                    <button type="reset" class="cancel-btn">Batal</button>
                    <button type="submit" class="submit-btn">Kirim</button>
                </div>
            </form>

        </div>
    </div>
    <script>
        document.getElementById('gambar').addEventListener('change', function (event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview-image');
            const uploadText = document.getElementById('upload-text');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    uploadText.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lon;

                    // Pakai OSM Nominatim untuk reverse geocoding
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                        .then(response => response.json())
                        .then(data => {
                            const displayName = data.display_name;
                            document.getElementById('alamat').textContent = displayName;
                            document.getElementById('alamat-wrapper').style.display = 'block';
                        })

                        .catch(error => {
                            document.getElementById('alamat').textContent = "Gagal mengambil alamat.";
                            console.error("Nominatim error:", error);
                        });

                    alert('Lokasi berhasil ditambahkan!');
                }, function (error) {
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            alert("Pengguna menolak permintaan Geolokasi.");
                            break;
                        case error.POSITION_UNAVAILABLE:
                            alert("Informasi lokasi tidak tersedia.");
                            break;
                        case error.TIMEOUT:
                            alert("Permintaan untuk mendapatkan lokasi pengguna telah timeout.");
                            break;
                        case error.UNKNOWN_ERROR:
                            alert("Terjadi kesalahan yang tidak diketahui.");
                            break;
                    }
                });
            } else {
                alert("Browser tidak mendukung Geolokasi.");
            }
        }
    </script>


    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ e(session("success")) }}',
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

    <script>
        function validateForm() {
            const judul = document.getElementById('judul').value.trim();
            const gambar = document.getElementById('gambar').files.length;
            const latitude = document.getElementById('latitude').value.trim();
            const longitude = document.getElementById('longitude').value.trim();

            if (judul === '') {
                alert('Judul laporan wajib diisi.');
                return false;
            }
            if (gambar === 0) {
                alert('Silakan upload gambar terlebih dahulu.');
                return false;
            }
            if (latitude === '' || longitude === '') {
                alert('Silakan tambahkan lokasi terlebih dahulu.');
                return false;
            }

            return true; // semua valid, form dikirim
        }
    </script>


    <style>
        body {
            font-family: 'Red Hat Text', sans-serif;
            margin: 0;
            padding: 0;
        }

        .main-wrapper {
            background-color: #ffffff;
            min-height: calc(100vh - 100px);
            padding: 50px 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .form-wrapper {
            background-color: #299E63;
            padding: 50px 50px;
            border-radius: 16px;
            width: 100%;
            max-width: 1000px;
            color: #333;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.2);
            min-height: 900px;

            background-image: url('assets/images/Masyarakat/aksenrecyle2.png');
            background-repeat: no-repeat;
            background-position: top right;
            background-size: 230px;
        }

        h2 {
            font-weight: 600;
            margin-bottom: 20px;
            color: #ffffff;
        }

        label {
            color: #ffffff;
            display: block;
            margin: 15px 0 5px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        input[type="text"],
        textarea {
            font-size: 1rem;
            width: 100%;
            padding: 18px;
            border-radius: 10px;
            border: 1px solid #ccc;
            outline: none;
            font-family: inherit;
        }

        .upload-box {
            background-color: #f9f9f9;
            height: 250px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            color: #555;
            cursor: pointer;
            font-weight: 500;
            margin-bottom: 10px;
            border: 2px solid #ccc;
        }

        .upload-box img {
            border-radius: 10px;
        }


        .location-btn {
            display: flex;
            align-items: center;
            background-color: white;
            color: #299E63;
            border: none;
            padding: 13px 20px;
            font-size: 0.92rem;
            font-weight: 550;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .location-btn:hover {
            background-color: #f0f0f0;
        }

        .location-btn .icon {
            font-size: 20px;
            color: orange;
            margin-right: 8px;
        }

        .alamat-box {
            margin-top: 20px;
            background-color: #ffffff;
            border-left: 5px solid #299E63;
            padding: 15px 20px;
            border-radius: 13px;
            font-size: 0.95rem;
            color: #299E63;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alamat-box i {
            color: #FFB800;
            font-size: 1.2rem;
        }


        .buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .buttons button {
            padding: 10px 24px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            font-size: 1rem;
        }

        .cancel-btn {
            background-color: white;
            color: #FFB800;
            border: 2px solid #FFB800;
        }

        .submit-btn {
            background-color: #FFB800;
            color: white;
        }
    </style>
@endsection