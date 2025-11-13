@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Crear Nuevo Evento</h2>

    <form action="{{ route('eventos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" id="titulo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="fecha_hora" class="form-label">Fecha y Hora</label>
            <input type="datetime-local" name="fecha_hora" id="fecha_hora" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="categorias" class="form-label">Categorías</label>
            <select name="categorias[]" id="categorias" class="form-select" multiple required>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen</label>
            <input type="file" name="imagen" id="imagen" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Ubicación del evento</label>

            <input type="hidden" name="latitud" id="latitud">
            <input type="hidden" name="longitud" id="longitud">

            <div id="map" style="height: 400px; width: 100%; border-radius: 10px;"></div>
        </div>

        <script>
            let map;
            let marker;

            function initMap() {
                const uruguayCenter = { lat: -34.9011, lng: -56.1645 }; // Montevideo

                map = new google.maps.Map(document.getElementById("map"), {
                    center: uruguayCenter,
                    zoom: 12,
                });

                map.addListener("click", (e) => {
                    placeMarker(e.latLng);
                });
            }

            function placeMarker(location) {
                if (marker) {
                    marker.setPosition(location);
                } else {
                    marker = new google.maps.Marker({
                        position: location,
                        map: map,
                    });
                }

                // Guardar las coordenadas en los campos ocultos
                document.getElementById("latitud").value = location.lat();
                document.getElementById("longitud").value = location.lng();
            }
        </script>

        <button type="submit" class="btn btn-primary">Crear Evento</button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
    {{-- Script de Google Maps --}}
        <script async src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}&callback=initMap"></script>
@endsection
