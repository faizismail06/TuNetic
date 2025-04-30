@extends('layouts.app')

@push('css')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        #map { height: 100vh; width: 100%; }
        body, html { margin: 0; padding: 0; height: 100%; }
    </style>
@endpush

@section('content')
    <div id="map"></div>
@endsection

@push('js')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([-7.056325, 110.454250], 15); // Contoh: Tembalang

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const ruteArmada = @json($ruteArmada);

        ruteArmada.forEach(function (item) {
            const marker = L.marker([item.latitude, item.longitude]).addTo(map);
            marker.bindPopup(`<b>${item.nama_lokasi}</b><br>Lat: ${item.latitude}, Lng: ${item.longitude}`);
        });

        // Opsional: Menampilkan info koordinat saat klik peta
        const popup = L.popup();
        map.on("click", function (e) {
            popup
                .setLatLng(e.latlng)
                .setContent(`Klik di ${e.latlng.toString()}`)
                .openOn(map);
        });
    </script>
@endpush
