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

    <style>
        body {
            font-family: 'Red Hat Text', sans-serif;
            margin: 0;
            padding: 0;
        }

        .main-wrapper {
            background-color: #ffffff;
            min-height: calc(100vh - 100px);
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .form-wrapper {
            background-color: #299E63;
            padding: 40px;
            border-radius: 16px;
            width: 100%;
            max-width: 1000px;
            color: #333;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.2);
            min-height: 900px;
            background-image: url('{{ asset('assets/images/Masyarakat/aksenrecyle2.png') }}');
            background-repeat: no-repeat;
            background-position: top right;
            background-size: 150px;
            position: relative;
        }

        h2 {
            font-weight: 600;
            margin-bottom: 20px;
            color: #ffffff;
            font-size: 1.8rem;
        }

        label {
            color: #ffffff;
            display: block;
            margin: 15px 0 8px;
            font-weight: 600;
            font-size: 1rem;
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
            box-sizing: border-box;
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
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23999' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 15px;
            box-sizing: border-box;
        }

        .upload-box {
            background-color: #f9f9f9;
            height: 250px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 12px;
            color: #555;
            cursor: pointer;
            font-weight: 500;
            margin-bottom: 10px;
            border: 2px solid #ccc;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            padding: 15px;
        }

        .upload-box:hover {
            border-color: #299E63;
            background-color: #f0f8f0;
            transform: translateY(-2px);
            /* Efek hover ringan */
            box-shadow: 0 4px 12px rgba(41, 158, 99, 0.15);
        }

        .upload-box #upload-text {
            font-size: 1.1rem;
            color: #666;
            z-index: 1;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .upload-box #preview-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 8px;
            z-index: 2;
        }

        #map {
            height: 300px;
            margin-bottom: 20px;
            border-radius: 10px;
            border: 2px solid #ccc;
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
            flex-shrink: 0;
        }

        .buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .buttons button {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            font-size: 1rem;
            min-width: 100px;
            transition: all 0.3s ease;
        }

        .cancel-btn {
            background-color: white;
            color: #FFB800;
            border: 2px solid #FFB800;
        }

        .cancel-btn:hover {
            background-color: #FFB800;
            color: white;
        }

        .submit-btn {
            background-color: #FFB800;
            color: white;
        }

        .submit-btn:hover {
            background-color: #e6a500;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-wrapper {
                padding: 10px;
            }

            .form-wrapper {
                padding: 20px;
                background-size: 100px;
                background-position: top right 10px;
            }

            h2 {
                font-size: 1.5rem;
                margin-bottom: 15px;
            }

            label {
                font-size: 0.9rem;
                margin: 12px 0 6px;
            }

            input[type="text"],
            textarea,
            .custom-select-wrapper select {
                padding: 15px;
                font-size: 0.9rem;
            }

            .upload-box {
                height: 200px;
            }

            #map {
                height: 250px;
            }

            .alamat-box {
                padding: 12px 15px;
                font-size: 0.85rem;
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }

            .alamat-box i {
                margin-bottom: 5px;
            }

            .buttons {
                justify-content: center;
                gap: 15px;
            }

            .buttons button {
                flex: 1;
                max-width: 150px;
                padding: 12px 20px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .form-wrapper {
                padding: 15px;
                background-size: 80px;
            }

            h2 {
                font-size: 1.3rem;
            }

            .upload-box {
                height: 180px;
            }

            #map {
                height: 220px;
            }

            input[type="text"],
            textarea,
            .custom-select-wrapper select {
                padding: 12px;
            }

            .buttons button {
                padding: 10px 15px;
                font-size: 0.85rem;
            }
        }

        /* Leaflet responsive fixes */
        .leaflet-control-container {
            font-size: 12px;
        }

        @media (max-width: 480px) {
            .leaflet-control-container {
                font-size: 10px;
            }

            .leaflet-control-geocoder {
                width: 200px !important;
            }
        }

        /* CSS tambahan untuk input masalah lainnya */
        #masalah-lainnya-box {
            margin-top: 15px;
            animation: slideDown 0.3s ease-in-out;
        }

        #masalah-lainnya-box label {
            margin-bottom: 8px;
            margin-top: 0;
        }

        #masalah-lainnya-box input {
            font-family: 'Red Hat Text', sans-serif;
            transition: border-color 0.3s ease;
        }

        #masalah-lainnya-box input:focus {
            border-color: #299E63;
            box-shadow: 0 0 0 2px rgba(41, 158, 99, 0.2);
        }

        /* Animasi slide down */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="main-wrapper">
        <div class="form-wrapper">
            <h2>Laporan Sampah Baru</h2>

            <form action="{{ route('lapor.submit') }}" method="POST" enctype="multipart/form-data"
                onsubmit="return validateForm()">

                @csrf

                <input type="hidden" name="id_user" value="{{ auth()->user()->id }}">

                <label for="judul">Judul Laporan</label>
                <input type="text" name="judul" id="judul" placeholder="Masukkan judul laporan" autocomplete="off" required>

                <label for="gambar">Upload Foto</label>
                <div class="upload-box" onclick="document.getElementById('gambar').click();">
                    <span id="upload-text">Pilih Gambar</span>
                    <img id="preview-image" src="#" alt="Preview Gambar" style="display:none;" />
                </div>
                <input type="file" id="gambar" name="gambar" accept="image/*" style="display:none;" required>

                <label>Pilih Lokasi di Peta</label>
                <div id="map"></div>

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
                        <option value="" disabled selected>Pilih Jenis Masalah</option>
                        <option value="Tumpukan Sampah">Tumpukan Sampah</option>
                        <option value="TPS Penuh">TPS Penuh</option>
                        <option value="Bau Menyengat">Bau Menyengat</option>
                        <option value="Pembuangan Liar">Pembuangan Liar</option>
                        <option value="Sampah Berserakan">Sampah Berserakan</option>
                        <option value="Saluran Air Tersumbat">Saluran Air Tersumbat</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div id="masalah-lainnya-box" style="display:none;">
                    <label for="masalah_lainnya">Jenis Masalah Lainnya</label>
                    <input type="text" name="masalah_lainnya" id="masalah_lainnya"
                        placeholder="Tulis jenis masalah lainnya..." autocomplete="off" />
                </div>
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="5" placeholder="Tulis deskripsi..." required></textarea>

                <input type="hidden" name="status" value="0">

                <div class="buttons">
                    <button type="button" class="cancel-btn" onclick="resetForm()">Batal</button>
                    <button type="submit" class="submit-btn">Kirim</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Image Preview Handler
        document.getElementById('gambar').addEventListener('change', function (event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview-image');
            const uploadText = document.getElementById('upload-text');

            if (file) {
                // Validasi tipe file
                if (!file.type.startsWith('image/')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File tidak valid',
                        text: 'Harap pilih file gambar yang valid.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    this.value = '';
                    return;
                }

                // Validasi ukuran file (maksimal 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File terlalu besar',
                        text: 'Ukuran file maksimal 5MB.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    uploadText.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                // Reset preview jika file dihapus
                preview.style.display = 'none';
                uploadText.style.display = 'block';
                preview.src = '#';
            }
        });

        // Form Validation
        function validateForm() {
            const judul = document.getElementById('judul').value.trim();
            const gambar = document.getElementById('gambar').files.length;
            const latitude = document.getElementById('latitude').value.trim();
            const longitude = document.getElementById('longitude').value.trim();
            const jenisMasalah = document.getElementById('jenis_masalah').value;
            const deskripsi = document.getElementById('deskripsi').value.trim();

            if (judul === '') {
                showAlert('warning', 'Judul kosong', 'Judul laporan wajib diisi.');
                document.getElementById('judul').focus();
                return false;
            }

            if (gambar === 0) {
                showAlert('warning', 'Gambar belum dipilih', 'Silakan upload gambar terlebih dahulu.');
                return false;
            }

            if (latitude === '' || longitude === '') {
                showAlert('warning', 'Lokasi belum dipilih', 'Silakan pilih lokasi di peta terlebih dahulu.');
                return false;
            }

            if (jenisMasalah === '') {
                showAlert('warning', 'Jenis masalah belum dipilih', 'Silakan pilih jenis masalah.');
                document.getElementById('jenis_masalah').focus();
                return false;
            }

            if (jenisMasalah === 'Lainnya') {
                const lainnya = document.getElementById('masalah_lainnya').value.trim();
                if (lainnya === '') {
                    showAlert('warning', 'Masalah lainnya kosong', 'Silakan tulis jenis masalah lainnya.');
                    document.getElementById('masalah_lainnya').focus();
                    return false;
                }
                if (lainnya.length < 5) {
                    showAlert('warning', 'Deskripsi terlalu singkat', 'Masalah lainnya minimal 5 karakter.');
                    document.getElementById('masalah_lainnya').focus();
                    return false;
                }
            }

            if (deskripsi === '') {
                showAlert('warning', 'Deskripsi kosong', 'Deskripsi wajib diisi.');
                document.getElementById('deskripsi').focus();
                return false;
            }

            if (deskripsi.length < 10) {
                showAlert('warning', 'Deskripsi terlalu singkat', 'Deskripsi minimal 10 karakter.');
                document.getElementById('deskripsi').focus();
                return false;
            }

            return true;
        }

        function showAlert(icon, title, text) {
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                timer: 2000,
                showConfirmButton: false
            });
        }

        // Show/Hide custom problem input
        function checkMasalahLainnya() {
            const jenisMasalah = document.getElementById('jenis_masalah').value;
            const masalahLainnyaBox = document.getElementById('masalah-lainnya-box');
            const masalahLainnyaInput = document.getElementById('masalah_lainnya');

            if (jenisMasalah === 'Lainnya') {
                masalahLainnyaBox.style.display = 'block';
                masalahLainnyaInput.required = true;
                // Focus ke input setelah animasi selesai
                setTimeout(() => {
                    masalahLainnyaInput.focus();
                }, 100);
            } else {
                masalahLainnyaBox.style.display = 'none';
                masalahLainnyaInput.required = false;
                masalahLainnyaInput.value = '';
            }
        }

        // Reset form function
        function resetForm() {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin membatalkan? Semua data yang diisi akan hilang.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#FFB800',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, batalkan',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('form').reset();
                    document.getElementById('preview-image').style.display = 'none';
                    document.getElementById('upload-text').style.display = 'block';
                    document.getElementById('alamat-wrapper').style.display = 'none';
                    document.getElementById('masalah-lainnya-box').style.display = 'none';
                    if (window.marker) {
                        window.marker.remove();
                        window.marker = null;
                    }
                    document.getElementById('latitude').value = '';
                    document.getElementById('longitude').value = '';
                }
            });
        }

        // Map functionality
        let map;
        let marker = null;

        function setMarker(lat, lng) {
            if (marker) {
                marker.remove();
            }

            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            window.marker = marker; // Make accessible globally

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            // Handle marker drag
            marker.on('dragend', function (e) {
                const position = e.target.getLatLng();
                setMarker(position.lat, position.lng);
            });

            // Get address
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

        // Initialize map
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                map = L.map('map').setView([lat, lng], 16);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                setMarker(lat, lng);

                map.on('click', function (e) {
                    setMarker(e.latlng.lat, e.latlng.lng);
                });

                L.Control.geocoder({
                    geocoder: L.Control.Geocoder.nominatim({
                        geocodingQueryParams: {
                            countrycodes: 'ID'
                        }
                    })
                }).addTo(map);

            }, function () {
                // Fallback ke lokasi default (Jakarta) jika geolocation gagal
                const defaultLat = -6.2088;
                const defaultLng = 106.8456;

                map = L.map('map').setView([defaultLat, defaultLng], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                map.on('click', function (e) {
                    setMarker(e.latlng.lat, e.latlng.lng);
                });

                L.Control.geocoder({
                    geocoder: L.Control.Geocoder.nominatim({
                        geocodingQueryParams: {
                            countrycodes: 'ID'
                        }
                    })
                }).addTo(map);

                Swal.fire({
                    icon: 'info',
                    title: 'Lokasi tidak tersedia',
                    text: 'Gagal mendapatkan lokasi. Silakan pilih lokasi secara manual di peta.',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Browser tidak mendukung',
                text: 'Browser tidak mendukung Geolokasi.',
                timer: 3000,
                showConfirmButton: false
            });
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

@endsection