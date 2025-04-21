@extends('layouts.app')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        #map { height: 500px; }
    </style>
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <h4 class="mb-3">Peta Lokasi TPS</h4>
            <div id="map"></div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid mt-4">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h5 class="m-0">Daftar Lokasi TPS</h5>
                    <div class="card-tools">
                        <a href="{{ route('lokasi-tps.create') }}" class="btn btn-sm btn-primary">
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
                                        <a href="{{ route('lokasi-tps.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('lokasi-tps.destroy', $item->id) }}" method="POST" class="d-inline">
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
@endsection

@push('js')
    <!-- DataTables JS -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            // Cegah duplikasi inisialisasi DataTable
            if ($.fn.DataTable.isDataTable('#datatable-main')) {
                $('#datatable-main').DataTable().clear().destroy();
            }


        });
    </script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([-7.056325, 110.454250], 15); // Fokus ke Tembalang

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const lokasiTps = @json($lokasiTps);

        lokasiTps.forEach(function (item) {
            const marker = L.marker([item.latitude, item.longitude]).addTo(map);
            marker.bindPopup(`<b>${item.nama_lokasi}</b><br>Lat: ${item.latitude}, Lng: ${item.longitude}`);
        });

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
