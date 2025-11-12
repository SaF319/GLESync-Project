@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Editar Evento</h2>

    {{-- Mostrar errores --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('eventos.update', $evento->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Título --}}
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" id="titulo" class="form-control" value="{{ old('titulo', $evento->titulo) }}" required>
        </div>

        {{-- Descripción --}}
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="4" required>{{ old('descripcion', $evento->descripcion) }}</textarea>
        </div>

        {{-- Fecha y Hora --}}
        <div class="mb-3">
            <label for="fecha_hora" class="form-label">Fecha y Hora</label>
            <input type="datetime-local" name="fecha_hora" id="fecha_hora" class="form-control"
                    value="{{ old('fecha_hora', $evento->fechasHoras->first() ? $evento->fechasHoras->first()->fecha_hora->format('Y-m-d\TH:i') : '') }}" required>
        </div>

        {{-- Categorías --}}
        <div class="mb-3">
            <label for="categorias" class="form-label">Categorías</label>
            <select name="categorias[]" id="categorias" class="form-select" multiple required>
                @foreach($todasCategorias as $categoria)
                    <option value="{{ $categoria->id }}"
                        @if(in_array($categoria->id, $evento->categorias->pluck('id')->toArray())) selected @endif>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Mantén presionada la tecla Ctrl (o Cmd) para seleccionar múltiples categorías.</small>
        </div>

        {{-- Imagen --}}
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen</label>
            <input type="file" name="imagen" id="imagen" class="form-control">
            @if($evento->imagen)
            <img src="{{ isset($evento['imagen']) && !empty($evento['imagen']['ruta']) ? asset($evento['imagen']['ruta']) : asset('imagenes/no_image.png') }}" class="d-block w-100" alt="Evento">
            @endif
        </div>

        {{-- Ubicación (Google Maps) --}}
        <div class="mb-3">
            <label class="form-label">Ubicación del evento</label>
            <div id="map" style="height: 400px; border-radius: 8px;"></div>

            <input type="hidden" name="latitud" id="latitud" value="{{ old('latitud', $evento->latitud ?? '') }}">
            <input type="hidden" name="longitud" id="longitud" value="{{ old('longitud', $evento->longitud ?? '') }}">
        </div>

        <button type="submit" class="btn btn-dark">Actualizar Evento</button>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancelar</a>
    </form>
</div>

{{-- Script de Google Maps --}}
<script>
    let map;
    let marker;

    function initMap() {
        const latitud = parseFloat(document.getElementById('latitud').value) || -34.9011;
        const longitud = parseFloat(document.getElementById('longitud').value) || -56.1645;

        const initialPosition = { lat: latitud, lng: longitud };

        map = new google.maps.Map(document.getElementById('map'), {
            center: initialPosition,
            zoom: 14,
        });

        marker = new google.maps.Marker({
            position: initialPosition,
            map: map,
            draggable: true,
        });

        // Actualizar coordenadas al mover el marcador
        marker.addListener('dragend', function (event) {
            document.getElementById('latitud').value = event.latLng.lat();
            document.getElementById('longitud').value = event.latLng.lng();
        });

        // Click en el mapa para mover el marcador
        map.addListener('click', function (event) {
            marker.setPosition(event.latLng);
            document.getElementById('latitud').value = event.latLng.lat();
            document.getElementById('longitud').value = event.latLng.lng();
        });
    }
</script>

<script async src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}&callback=initMap"></script>
@endsection
