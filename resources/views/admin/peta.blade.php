@extends('admin.template')

@section('title', 'Peta Desa ' . $desa->nama)

@section('libs')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.11.0/css/ol.css" type="text/css">
<style>
    .map {
        height: 400px;
        width: 100%;
    }
</style>
<script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.11.0/build/ol.js"></script>
@endsection

@section('content')
<h1 class="text-center mb-4">Peta {{ $desa->nama }}</h1><hr>
<button type="button" class="btn btn-primary mb-4" data-toggle-display="#ubah-lokasi" onclick="ubah(this)"><i class="fas fa-edit fa-fw"></i> Edit lokasi</button>
<div id="ubah-lokasi" class="d-none">
    <div>
        <input type="checkbox" id="lokasi-otomatis" data-toggle-display="#lokasi-manual"> <label for="lokasi-otomatis">Gunakan Lokasi saat ini</label>
    </div>
    <form action="/admin/peta/ubah?desa={{ $desa->id }}" method="post" id="lokasi-manual" style="max-width: 18rem; background-color: #dddddd" class="p-3 mt-3 mb-4">
        @csrf
        <div>
            <label class="form-label" for="lokasi-longitude">Masukan longitude Lokasi</label>
            <input class="form-control" type="number" id="lokasi-longitude" name="lokasi-longitude" step="any">
        </div>
        <div>
            <label class="form-label" for="lokasi-latitude">Masukan latitude Lokasi</label>
            <input class="form-control" type="number" id="lokasi-latitude" name="lokasi-latitude" step="any">
        </div>
        <button class="btn btn-primary mt-4" type="submit">Simpan</button>
        <button class="btn btn-secondary mt-4" type="button" onclick="showLocation(false)">Lihat</button>
    </form>
</div>
<div id="map" class="map"></div>

<script type="text/javascript">
    const map = new ol.Map({
        target: 'map',
        layers: [
            new ol.layer.Tile({
                source: new ol.source.OSM()
            })
        ],
        view: new ol.View({
            center: ol.proj.fromLonLat([<?= $desa->lokasi ?>]),
            zoom: 13
        })
    });

    function ubah(element) {
        const target = document.querySelector(element.getAttribute('data-toggle-display'));
        target.classList.toggle("d-none");
        if (!target.classList.contains('d-none') && document.getElementById('lokasi-otomatis').checked) {
            showLocation();
        }
    }

    document.getElementById('lokasi-otomatis').onclick = function() {
        if (this.checked) {
            showLocation();
        }
    }

    function showLocation(auto = true) {
        const lokasiLongitude = document.getElementById('lokasi-longitude');
        const lokasiLatitude = document.getElementById('lokasi-latitude');

        if (auto) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const coords = position.coords;

                lokasiLongitude.value = coords.longitude;
                lokasiLatitude.value = coords.latitude;

                map.getView().setCenter(ol.proj.fromLonLat([coords.longitude, coords.latitude]));
            });
        } else {
            map.getView().setCenter(ol.proj.fromLonLat([lokasiLongitude.value, lokasiLatitude.value]));
        }
    }
</script>
@endsection