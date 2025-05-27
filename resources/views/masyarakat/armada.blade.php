@extends('components.navbar')

@push('css')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        #map { height: 80vh; width: 100%; }
        .info-panel {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .armada-list {
            max-height: 300px;
            overflow-y: auto;
        }
        .active-armada {
            background-color: #e7f3ff;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Panel Informasi -->
        <div class="col-md-3">
            <div class="info-panel">
                <h4>Tracking Armada</h4>
                <p>Pembaruan lokasi setiap 5 detik</p>

                <hr>

                <div class="form-group">
                    <label for="filter-jadwal">Filter Jadwal:</label>
                    <select id="filter-jadwal" class="form-control">
                        <option value="all">Semua Jadwal</option>
                        @foreach($ruteArmada as $jadwal)
                            <option value="{{ $jadwal->id }}">{{ $jadwal->nama_jadwal }}</option>
                        @endforeach
                    </select>
                </div>

                <hr>

                <h5>Daftar Armada Aktif</h5>
                <div class="armada-list" id="armada-list">
                    <!-- Daftar armada akan diisi via JavaScript -->
                </div>
            </div>
        </div>

        <!-- Peta -->
        <div class="col-md-9">
            <div id="map"></div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        // Inisialisasi peta
        const map = L.map('map').setView([-7.056325, 110.454250], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Variabel untuk menyimpan marker
        const markers = {};
        let polylines = {};

        // Fungsi untuk memuat data tracking armada
        function loadTrackingData() {
            const filterJadwal = document.getElementById('filter-jadwal').value;

            fetch(`/api/tracking-armada?filter_jadwal=${filterJadwal}`)
                .then(response => response.json())
                .then(data => {
                    updateMap(data);
                    updateArmadaList(data);
                })
                .catch(error => console.error('Error:', error));
        }

        // Fungsi untuk memperbarui peta
        function updateMap(data) {
            // Hapus marker lama yang tidak ada di data baru
            Object.keys(markers).forEach(id => {
                if (!data.find(item => item.id_jadwal_operasional == id)) {
                    map.removeLayer(markers[id]);
                    delete markers[id];

                    if (polylines[id]) {
                        map.removeLayer(polylines[id]);
                        delete polylines[id];
                    }
                }
            });

            // Mengelompokkan data berdasarkan id_jadwal_operasional
            const groupedData = data.reduce((acc, curr) => {
                if (!acc[curr.id_jadwal_operasional]) {
                    acc[curr.id_jadwal_operasional] = [];
                }
                acc[curr.id_jadwal_operasional].push(curr);
                return acc;
            }, {});

            // Memperbarui atau menambah marker
            Object.keys(groupedData).forEach(jadwalId => {
                const armadaData = groupedData[jadwalId];
                const latestData = armadaData[armadaData.length - 1]; // Data terbaru

                // Jika marker sudah ada, perbarui posisinya
                if (markers[jadwalId]) {
                    markers[jadwalId].setLatLng([latestData.latitude, latestData.longitude]);
                    markers[jadwalId].setPopupContent(`
                        <b>Armada: ${latestData.jadwal_operasional.armada.nama_armada || 'N/A'}</b><br>
                        Jadwal: ${latestData.jadwal_operasional.jadwal.nama_jadwal || 'N/A'}<br>
                        Status: ${getStatusLabel(latestData.jadwal_operasional.status)}<br>
                        Waktu: ${new Date(latestData.timestamp).toLocaleString('id-ID')}<br>
                        Koordinat: ${latestData.latitude}, ${latestData.longitude}
                    `);
                }
                // Jika belum ada, buat marker baru
                else {
                    const marker = L.marker([latestData.latitude, latestData.longitude]).addTo(map);
                    marker.bindPopup(`
                        <b>Armada: ${latestData.jadwal_operasional.armada.nama_armada || 'N/A'}</b><br>
                        Jadwal: ${latestData.jadwal_operasional.jadwal.nama_jadwal || 'N/A'}<br>
                        Status: ${getStatusLabel(latestData.jadwal_operasional.status)}<br>
                        Waktu: ${new Date(latestData.timestamp).toLocaleString('id-ID')}<br>
                        Koordinat: ${latestData.latitude}, ${latestData.longitude}
                    `);
                    markers[jadwalId] = marker;
                }

                // Membuat atau memperbarui polyline untuk menampilkan jejak
                const points = armadaData.map(item => [item.latitude, item.longitude]);

                if (polylines[jadwalId]) {
                    map.removeLayer(polylines[jadwalId]);
                }

                polylines[jadwalId] = L.polyline(points, {
                    color: getRandomColor(jadwalId),
                    weight: 3,
                    opacity: 0.7
                }).addTo(map);
            });
        }

        // Fungsi untuk memperbarui daftar armada
        function updateArmadaList(data) {
            const armadaList = document.getElementById('armada-list');

            // Mengelompokkan data berdasarkan id_jadwal_operasional
            const groupedData = data.reduce((acc, curr) => {
                if (!acc[curr.id_jadwal_operasional]) {
                    acc[curr.id_jadwal_operasional] = curr;
                }
                return acc;
            }, {});

            // Membuat HTML untuk daftar armada
            let html = '';

            Object.values(groupedData).forEach(item => {
                html += `
                    <div class="card mb-2 armada-item" data-id="${item.id_jadwal_operasional}">
                        <div class="card-body p-2">
                            <h6 class="mb-1">${item.jadwal_operasional.armada.nama_armada || 'N/A'}</h6>
                            <small class="text-muted">
                                Jadwal: ${item.jadwal_operasional.jadwal.nama_jadwal || 'N/A'}<br>
                                Status: ${getStatusLabel(item.jadwal_operasional.status)}<br>
                                Terakhir update: ${new Date(item.timestamp).toLocaleTimeString('id-ID')}
                            </small>
                        </div>
                    </div>
                `;
            });

            armadaList.innerHTML = html || '<p class="text-center">Tidak ada armada aktif</p>';

            // Menambahkan event listener untuk item armada
            document.querySelectorAll('.armada-item').forEach(item => {
                item.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    if (markers[id]) {
                        map.setView(markers[id].getLatLng(), 15);
                        markers[id].openPopup();

                        // Highlight item yang dipilih
                        document.querySelectorAll('.armada-item').forEach(el => {
                            el.classList.remove('active-armada');
                        });
                        this.classList.add('active-armada');
                    }
                });
            });
        }

        // Fungsi untuk mendapatkan label status
        function getStatusLabel(status) {
            const labels = {
                0: 'Belum Berjalan',
                1: 'Sedang Berjalan',
                2: 'Selesai'
            };
            return labels[status] || 'Tidak Diketahui';
        }

        // Fungsi untuk menghasilkan warna acak berdasarkan id
        function getRandomColor(id) {
            const colors = [
                '#3388ff', '#ff6b6b', '#4ecdc4', '#ffd166',
                '#06d6a0', '#118ab2', '#073b4c', '#ef476f'
            ];

            // Menggunakan id sebagai indeks untuk memilih warna
            return colors[id % colors.length];
        }

        // Load data pertama kali
        loadTrackingData();

        // Set interval untuk memuat data setiap 5 detik
        setInterval(loadTrackingData, 5000);

        // Event listener untuk filter
        document.getElementById('filter-jadwal').addEventListener('change', loadTrackingData);
    </script>
@endpush
