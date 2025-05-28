@extends('layouts.app')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        #map {
            height: 500px;
        }

        .filter-container {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        .legend {
            padding: 10px;
            background: white;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-top: 10px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            margin-right: 8px;
            border-radius: 50%;
        }

        .tps-color {
            background-color: #28a745;
        }

        .tpst-color {
            background-color: #17a2b8;
        }

        .tpa-color {
            background-color: #ffc107;
        }
    </style>
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <h4 class="mb-3">Peta Lokasi TPS</h4>

            <!-- Filter berdasarkan kategori -->
            <div class="filter-container">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Filter berdasarkan kategori:</h5>
                        <div class="btn-group" role="group">
                            <a href="{{ route('lokasi-tps.index') }}"
                                class="btn btn-outline-secondary {{ !request()->has('tipe') ? 'active' : '' }}">Semua</a>
                            <a href="{{ route('lokasi-tps.filterByTipe', 'TPS') }}"
                                class="btn btn-outline-primary {{ request()->tipe == 'TPS' ? 'active' : '' }}">TPS</a>
                            <a href="{{ route('lokasi-tps.filterByTipe', 'TPST') }}"
                                class="btn btn-outline-info {{ request()->tipe == 'TPST' ? 'active' : '' }}">TPST</a>
                            <a href="{{ route('lokasi-tps.filterByTipe', 'TPA') }}"
                                class="btn btn-outline-warning {{ request()->tipe == 'TPA' ? 'active' : '' }}">TPA</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="legend">
                            <h6>Keterangan:</h6>
                            <div class="legend-item">
                                <div class="legend-color tps-color"></div>
                                <span>TPS (Tempat Pembuangan Sampah)</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color tpst-color"></div>
                                <span>TPST (Tempat Pembuangan Sampah Terpadu)</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color tpa-color"></div>
                                <span>TPA (Tempat Pembuangan Akhir)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="map"></div>

            <div class="content">
                <div class="container-fluid mt-4">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h5 class="m-0">Daftar Lokasi TPS</h5>
                            <div class="card-tools">
                                <a href="{{ route('lokasi-tps.create') }}" class="btn btn-sm btn-success ml-auto">
                                    <i class="fas fa-plus-circle"></i> Tambah Lokasi TPS
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="datatable-main" class="table table-bordered table-striped text-sm">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Lokasi</th>
                                        <th>Kategori</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Wilayah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lokasiTps as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama_lokasi }}</td>
                                            <td>
                                                @if ($item->tipe == 'TPS')
                                                    <span class="badge badge-success">TPS</span>
                                                @elseif($item->tipe == 'TPST')
                                                    <span class="badge badge-info">TPST</span>
                                                @elseif($item->tipe == 'TPA')
                                                    <span class="badge badge-warning">TPA</span>
                                                @else
                                                    <span class="badge badge-secondary">Tidak Diketahui</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->latitude }}</td>
                                            <td>{{ $item->longitude }}</td>
                                            <td>
                                                {{ $item->village->name ?? '-' }},
                                                {{ $item->district->name ?? '-' }},
                                                {{ $item->regency->name ?? '-' }},
                                                {{ $item->province->name ?? '-' }}
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <a href="{{ route('lokasi-tps.edit', $item->id) }}"
                                                        class="btn btn-sm btn-warning">Edit</a>
                                                    <form action="{{ route('lokasi-tps.destroy', $item->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger confirm-button">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- <!-- DataTables JS -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

    <!-- DataTables Initialization -->
    <script>
        $(document).ready(function() {
            // Pastikan destroy DataTable yang ada terlebih dahulu
            if ($.fn.DataTable.isDataTable('#datatable-main')) {
                $('#datatable-main').DataTable().destroy();
                console.log("DataTable sudah ada, menghapus instance sebelumnya");
            }

            // Inisialisasi DataTable baru
            $('#datatable-main').DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
                }
            });

            // Konfirmasi sebelum hapus
            $('.confirm-button').click(function(e) {
                if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    e.preventDefault();
                }
            });
        });
    </script> --}}

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        // Inisialisasi peta dengan koordinat default (Tembalang)
        // Akan diubah ke lokasi pengguna jika geolocation berhasil
        const map = L.map('map').setView([-7.056325, 110.454250], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Variabel untuk menyimpan marker lokasi pengguna
        let userLocationMarker = null;

        // Fungsi untuk mendapatkan dan menampilkan lokasi pengguna
        function getUserLocation() {
            if (navigator.geolocation) {
                // Tampilkan loading indicator (opsional)
                console.log("Mencari lokasi pengguna...");

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;
                        const accuracy = position.coords.accuracy;

                        console.log(`Lokasi pengguna ditemukan: ${userLat}, ${userLng}`);

                        // Pindahkan view peta ke lokasi pengguna
                        map.setView([userLat, userLng], 15);

                        // // Buat marker untuk lokasi pengguna
                        // const userIcon = L.divIcon({
                        //     className: 'user-location-icon',
                        //     html: `<div style="
                    //         background-color: #007bff;
                    //         width: 20px;
                    //         height: 20px;
                    //         border-radius: 50%;
                    //         border: 3px solid #fff;
                    //         box-shadow: 0 0 10px rgba(0,123,255,0.5);
                    //         position: relative;
                    //     ">
                    //         <div style="
                    //             position: absolute;
                    //             top: 50%;
                    //             left: 50%;
                    //             transform: translate(-50%, -50%);
                    //             width: 8px;
                    //             height: 8px;
                    //             background-color: #fff;
                    //             border-radius: 50%;
                    //         "></div>
                    //     </div>`,
                        //     iconSize: [20, 20],
                        //     iconAnchor: [10, 10]
                        // });

                        // // Hapus marker lokasi pengguna sebelumnya jika ada
                        // if (userLocationMarker) {
                        //     map.removeLayer(userLocationMarker);
                        // }

                        // // Tambahkan marker lokasi pengguna
                        // userLocationMarker = L.marker([userLat, userLng], {
                        //     icon: userIcon
                        // }).addTo(map);

                        // userLocationMarker.bindPopup(`
                    //     <b>📍 Lokasi Anda</b><br>
                    //     Koordinat: ${userLat.toFixed(6)}, ${userLng.toFixed(6)}<br>
                    //     Akurasi: ±${Math.round(accuracy)} meter
                    // `);

                        // // Tambahkan circle untuk menunjukkan akurasi
                        // L.circle([userLat, userLng], {
                        //     radius: accuracy,
                        //     color: '#007bff',
                        //     fillColor: '#007bff',
                        //     fillOpacity: 0.1,
                        //     weight: 1
                        // }).addTo(map);

                    },
                    function(error) {
                        console.log("Error mendapatkan lokasi:", error.message);

                        let errorMessage = "";
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = "Akses lokasi ditolak oleh pengguna.";
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = "Informasi lokasi tidak tersedia.";
                                break;
                            case error.TIMEOUT:
                                errorMessage = "Waktu permintaan lokasi habis.";
                                break;
                            default:
                                errorMessage = "Terjadi kesalahan yang tidak diketahui.";
                                break;
                        }

                        // Tampilkan notifikasi error (opsional)
                        alert(
                            `Tidak dapat mendapatkan lokasi: ${errorMessage}\nMenggunakan lokasi default (Tembalang).`
                        );
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 300000 // Cache lokasi selama 5 menit
                    }
                );
            } else {
                console.log("Geolocation tidak didukung oleh browser ini.");
                alert("Browser Anda tidak mendukung geolocation.\nMenggunakan lokasi default (Tembalang).");
            }
        }

        // Panggil fungsi untuk mendapatkan lokasi pengguna
        getUserLocation();

        const lokasiTps = @json($lokasiTps);

        // Fungsi untuk mendapatkan icon berdasarkan tipe
        function getMarkerIcon(tipe) {
            if (tipe === 'TPS') {
                return L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background-color: #28a745; width: 15px; height: 15px; border-radius: 50%; border: 2px solid #fff;"></div>`,
                    iconSize: [15, 15],
                    iconAnchor: [7, 7]
                });
            } else if (tipe === 'TPST') {
                return L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background-color: #17a2b8; width: 15px; height: 15px; border-radius: 50%; border: 2px solid #fff;"></div>`,
                    iconSize: [15, 15],
                    iconAnchor: [7, 7]
                });
            } else if (tipe === 'TPA') {
                return L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background-color: #ffc107; width: 15px; height: 15px; border-radius: 50%; border: 2px solid #fff;"></div>`,
                    iconSize: [15, 15],
                    iconAnchor: [7, 7]
                });
            } else {
                return L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background-color: #6c757d; width: 15px; height: 15px; border-radius: 50%; border: 2px solid #fff;"></div>`,
                    iconSize: [15, 15],
                    iconAnchor: [7, 7]
                });
            }
        }

        // Fungsi untuk mendapatkan nama kategori
        function getKategoriName(tipe) {
            switch (tipe) {
                case 'TPS':
                    return 'TPS (Tempat Pembuangan Sampah)';
                case 'TPST':
                    return 'TPST (Tempat Pembuangan Sampah Terpadu)';
                case 'TPA':
                    return 'TPA (Tempat Pembuangan Akhir)';
                default:
                    return 'Tidak Diketahui';
            }
        }

        // Buat layer group untuk setiap kategori TPS
        const tpsLayer = L.layerGroup();
        const tpstLayer = L.layerGroup();
        const tpaLayer = L.layerGroup();
        const unknownLayer = L.layerGroup();

        // Tambahkan marker ke layer yang sesuai
        lokasiTps.forEach(function(item) {
            const marker = L.marker([item.latitude, item.longitude], {
                icon: getMarkerIcon(item.tipe)
            });

            marker.bindPopup(`
                <b>${item.nama_lokasi}</b><br>
                Kategori: ${getKategoriName(item.tipe)}<br>
                Lat: ${item.latitude}, Lng: ${item.longitude}<br>
                <small>${item.village?.name || '-'}, ${item.district?.name || '-'}</small>
            `);

            // Tambahkan ke layer yang sesuai
            if (item.tipe === 'TPS') {
                tpsLayer.addLayer(marker);
            } else if (item.tipe === 'TPST') {
                tpstLayer.addLayer(marker);
            } else if (item.tipe === 'TPA') {
                tpaLayer.addLayer(marker);
            } else {
                unknownLayer.addLayer(marker);
            }
        });

        // Tambahkan semua layer ke peta
        tpsLayer.addTo(map);
        tpstLayer.addTo(map);
        tpaLayer.addTo(map);
        unknownLayer.addTo(map);

        // Tambahkan kontrol layer
        const overlays = {
            "TPS": tpsLayer,
            "TPST": tpstLayer,
            "TPA": tpaLayer
        };

        L.control.layers(null, overlays).addTo(map);

        // Tambahan: Tombol untuk kembali ke lokasi pengguna
        L.Control.UserLocation = L.Control.extend({
            onAdd: function(map) {
                const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
                container.style.backgroundColor = 'white';
                container.style.backgroundImage =
                    "url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxwYXRoIGQ9Im0zIDExIDEzLTkgMS4yIDIuNCA2LjItLjlMMTQgMTdsMiA3LjYtNi0uOUw3IDIxWiIvPjwvc3ZnPg==')";
                container.style.backgroundSize = '20px 20px';
                container.style.backgroundPosition = 'center';
                container.style.backgroundRepeat = 'no-repeat';
                container.style.width = '34px';
                container.style.height = '34px';
                container.style.cursor = 'pointer';
                container.title = 'Kembali ke lokasi saya';

                container.onclick = function() {
                    getUserLocation();
                }

                return container;
            },

            onRemove: function(map) {
                // Nothing to do here
            }
        });

        L.control.userLocation = function(opts) {
            return new L.Control.UserLocation(opts);
        }

        L.control.userLocation({
            position: 'topright'
        }).addTo(map);

        // Tambahan: Fungsi klik di peta
        const popup = L.popup();

        function onMapClick(e) {
            popup
                .setLatLng(e.latlng)
                .setContent(`Kamu mengklik peta di ${e.latlng.toString()}`)
                .openOn(map);
        }

        map.on("click", onMapClick);
    </script>
@endpush
