@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Evento {{ $evento->titulo }}</h2>

    <div class="position-relative mb-3">
        {{-- Imagen --}}
        @if($evento->imagen)
            <img src="{{ asset($evento->imagen->ruta) }}" class="card-img-top" alt="{{ $evento->titulo }}" style="height: 400px; object-fit: cover;">
        @else
            <h3>Imagen no disponible</h3>
        @endif

        {{-- Overlay categorías y hora --}}
        <div class="position-absolute bottom-0 start-0 m-3 p-2 bg-dark bg-opacity-75 text-white rounded d-flex align-items-center gap-3 flex-wrap">
            {{-- Fecha y Hora --}}
            <div class="me-3">
                <h6 class="mb-1">Hora/s del Evento</h6>
                <small class="text-white-50">
                    <i class="bi bi-clock"></i>
                    {{ optional($evento->fechasHoras->first())->fecha_hora
                        ? $evento->fechasHoras->first()->fecha_hora->format('Y-m-d H:i')
                        : 'Fecha no disponible'
                    }}
                </small>
            </div>

            {{-- Categorías --}}
            <div>
                <h6 class="mb-1">Categorías</h6>
                <div>
                    @foreach($evento->categorias as $categoria)
                        <span class="badge bg-primary me-1 small">{{ $categoria->nombre }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Mostrar mensajes de éxito/error --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Descripción --}}
    <div class="mb-3">
        <h3>Descripción</h3>
        <div class="card">
            <div class="card-body">
                <p>{{ $evento->descripcion }}</p>
            </div>
        </div>
    </div>

     {{-- Mapa --}}
    <div id="map" style="height: 400px; width: 100%; border-radius: 10px;"></div>

    <script>
        function initMap() {
            const lat = parseFloat(@json($evento->latitud));
            const lng = parseFloat(@json($evento->longitud));

            if (!lat || !lng) {
                document.getElementById("map").innerHTML =
                    "<p class='text-danger mt-3'>Ubicación no disponible para este evento.</p>";
                return;
            }

            const location = { lat: lat, lng: lng };

            const map = new google.maps.Map(document.getElementById("map"), {
                center: location,
                zoom: 15,
            });

            new google.maps.Marker({
                position: location,
                map: map,
            });
        }
    </script>

    {{-- Sección de Comentarios --}}
    <div class="mb-3">
        <h2>Comentarios</h2>

        {{-- Formulario de comentarios --}}
        @auth
        <form action="{{ route('comentario.store', $evento->id) }}" method="POST">
            @csrf
            <input type="hidden" name="evento_id" value="{{ $evento->id }}">
            <div class="mb-3">
                <textarea class="form-control" id="comentario" name="comentario" rows="3"
                        placeholder="Escribe tu comentario..." required></textarea>
                @error('comentario')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Subir Comentario</button>
        </form>
        @else
        <div class="alert alert-info">
            <a href="{{ route('login') }}">Inicia sesión</a> para comentar.
        </div>
        @endauth

        {{-- Lista de comentarios --}}
        @if($comentarios->count() > 0)
            <div class="comentarios-list mt-4">
                <h4>Comentarios ({{ $comentarios->count() }})</h4>
                @foreach($comentarios as $comentario)
                    <div class="comentario-card border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $comentario->usuario->nombre ?? 'Anónimo' }}</strong>
                            <small class="text-muted">
                                {{ $comentario->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        <p class="mb-0 mt-1">{{ $comentario->comentario }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info mt-4">
                No hay comentarios para este evento. ¡Sé el primero en comentar!
            </div>
        @endif
    </div>

    {{-- Botón volver al Dashboard --}}
    <div class="mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-dark">
            ← Volver al Dashboard
        </a>
    </div>
</div>
    {{-- Script de Google Maps --}}
        <script async src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}&callback=initMap"></script>
@endsection

