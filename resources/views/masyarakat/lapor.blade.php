@extends('components.navbar')

@section('content')

    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Text:wght@400;500;550;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
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


                <label>Pilih Lokasi di Peta</label>
                <div id="map" style="height: 300px; margin-bottom: 20px; border-radius: 10px;"></div>

                <input type="hidden" name="latitude" id="latitude" required>
                <input type="hidden" name="longitude" id="longitude" required>
                <div id="alamat-wrapper" style="display: none;">
                    <div class="alamat-box">
                        <i class="fas fa-map-marker-alt"></i>
                        <span id="alamat">Alamat akan ditampilkan di sini</span>
                    </div>
                </div>
                <label for="jenis_masalah">Jenis Masalah</label>
                <div class="custom-select-wrapper">
                    <select name="jenis_masalah" id="jenis_masalah" onchange="checkMasalahLainnya()" required>
                        <option value="">-- Pilih Masalah --</option>
                        <option value="Tumpukan Sampah">Tumpukan Sampah</option>
                        <option value="TPS Penuh">TPS Penuh</option>
                        <option value="Bau Menyengat">Bau Menyengat</option>
                        <option value="Pembuangan Liar">Pembuangan Liar</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- Kolom input muncul jika "Lainnya" dipilih -->
                <div id="masalah-lainnya-box" style="display:none;">
                    <input type="text" name="masalah_lainnya" id="masalah_lainnya" placeholder="Tulis jenis masalah lainnya"
                        style="margin-top: 10px; font-size: 1rem; width: 100%; padding: 18px; border-radius: 10px; border: 1px solid #ccc;" />
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
                alert('Silakan pilih lokasi di peta terlebih dahulu.');
                return false;
            }

            return true;
        }
        if (document.getElementById('jenis_masalah').value === '') {
            alert('Silakan pilih jenis masalah.');
            return false;
        }
        if (document.getElementById('jenis_masalah').value === 'Lainnya') {
            const lainnya = document.getElementById('masalah_lainnya').value.trim();
            if (lainnya === '') {
                alert('Silakan tulis jenis masalah lainnya.');
                return false;
            }
        }
        <script>
    function checkMasalahLainnya() {
        const jenisMasalah = document.getElementById('jenis_masalah').value;
        const masalahLainnyaBox = document.getElementById('masalah-lainnya-box');

        if (jenisMasalah === 'Lainnya') {
            masalahLainnyaBox.style.display = 'block';
        } else {
            masalahLainnyaBox.style.display = 'none';
        }
    }
</script>

    </script>

    <script>
        let map;
        let marker = null;  // Marker global yang akan dipindahkan, bukan dibuat ulang

        function setMarker(lat, lng) {
            // Jika marker sudah ada, hapus dulu sebelum menambahkan yang baru
            if (marker) {
                marker.remove(); // Menghapus marker yang lama
            }

            // Tambahkan marker baru
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);

            // Update form koordinat
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            // Ambil dan tampilkan alamat
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    const addr = data.address;
                    const readableAlamat = [
                        addr.road || addr.neighbourhood || '',
                        addr.village || addr.suburb || addr.town || addr.city || '',
                        addr.state || '',
                        addr.country || ''
                    ].filter(Boolean).join(', ');
                    document.getElementById('alamat').textContent = readableAlamat;

                    document.getElementById('alamat-wrapper').style.display = 'block';
                })
                .catch(error => {
                    document.getElementById('alamat').textContent = "Gagal mengambil alamat.";
                    console.error("Geocoding error:", error);
                });
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                // Inisialisasi peta
                map = L.map('map').setView([lat, lng], 16);

                // Tambahkan tile OpenStreetMap
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // Pasang marker awal dari lokasi pengguna
                setMarker(lat, lng);

                // Ketika user klik peta
                map.on('click', function (e) {
                    setMarker(e.latlng.lat, e.latlng.lng);
                });

                // Tambahkan search control
                L.Control.geocoder({
                    geocoder: L.Control.Geocoder.nominatim({
                        geocodingQueryParams: {
                            countrycodes: 'ID'
                        }
                    })
                }).addTo(map);

                // Input pencarian manual
                document.getElementById('search-input').addEventListener('input', function () {
                    const query = this.value;
                    if (query.length > 2) {
                        fetch(`https://nominatim.openstreetmap.org/search?format=json&countrycodes=ID&q=${query}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.length > 0) {
                                    const lat = parseFloat(data[0].lat);
                                    const lon = parseFloat(data[0].lon);
                                    map.setView([lat, lon], 16);
                                    setMarker(lat, lon);  // Pastikan hanya satu marker yang dipindahkan
                                }
                            })
                            .catch(error => console.error("Geocoding error:", error));
                    }
                });

            }, function () {
                alert("Gagal mendapatkan lokasi. Aktifkan izin lokasi dan coba lagi.");
            });
        } else {
            alert("Browser tidak mendukung Geolokasi.");
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
            background-image: url('{{ asset('assets/images/Masyarakat/aksenrecyle2.png') }}');
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

        .custom-select-wrapper select {
            font-size: 1rem;
            width: 100%;
            padding: 18px;
            border-radius: 10px;
            border: 1px solid #ccc;
            outline: none;
            font-family: inherit;
            background-color: white;
            appearance: none; /* Hilangkan panah default */
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23999' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 10px;
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