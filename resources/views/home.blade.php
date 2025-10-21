@extends('layouts.app') {{-- usamos un layout general --}}

@section('content')
<div class="container">

    {{-- Helper para colores de estados --}}
    @php
        function getEstadoClass($estado) {
            return match(strtolower($estado)) {
                'futuro' => 'bg-success',    // Verde
                'presente' => 'bg-warning',  // Amarillo
                'pasado' => 'bg-secondary',  // Gris
                'suspendido' => 'bg-danger', // Rojo
                default => 'bg-info'         // Azul si no coincide
            };
        }
    @endphp

    {{-- Carrusel de eventos --}}
    <div id="eventCarousel" class="carousel slide mb-5" data-bs-ride="carousel" data-bs-interval="2000">
        <div class="carousel-inner">
            @foreach($eventos as $index => $evento)
                <div class="carousel-item @if($index == 0) active @endif">
                    <img src="{{ $evento['imagen_url'] ?? 'https://via.placeholder.com/1200x400' }}" class="d-block w-100" alt="Evento">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                        <h5>{{ $evento['titulo'] }}</h5>
                        <p>{{ \Illuminate\Support\Str::limit($evento['descripcion'], 100) }}</p>
                        <small class="badge {{ getEstadoClass($evento['estado']) }} fs-5">
                            {{ ucfirst($evento['estado']) }}
                        </small>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Botones de control --}}
        <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>


    {{-- Barra de búsqueda --}}
<div class="row justify-content-center mb-5">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body py-4">
                <h5 class="card-title text-center mb-3">🔍 Buscar Eventos</h5>
                <form action="{{ route('home.buscar') }}" method="GET">
                    <div class="input-group input-group-lg">
                        <input type="text"
                                name="q"
                                class="form-control"
                                placeholder="Buscar eventos por nombre, descripción..."
                                value="{{ old('q') }}"
                                required>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                    @error('q')
                        <div class="text-danger mt-2 text-center">{{ $message }}</div>
                    @enderror
                </form>
                @auth
                    <div class="text-center mt-2">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Buscarás solo en tus eventos organizados
                        </small>
                    </div>
                @else
                    <div class="text-center mt-2">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Búsqueda en todos los eventos públicos
                        </small>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

    {{-- Últimos eventos destacados --}}
    <h2 class="mb-4">Eventos destacados</h2>
    <div class="row">
        @foreach($eventos as $evento)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ $evento['imagen_url'] ?? 'https://via.placeholder.com/400x200' }}" class="card-img-top" alt="Evento">
                    <div class="card-body">
                        <h5 class="card-title">{{ $evento['titulo'] }}</h5>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit($evento['descripcion'], 80) }}</p>
                        <span class="badge {{ getEstadoClass($evento['estado']) }} fs-5">
                            {{ ucfirst($evento['estado']) }}
                        </span>
                    </div>
                    <div class="card-footer text-end">
                        {{-- Como no hay BD aún, se deja el enlace vacío o # --}}
                        <a href="#" class="btn btn-sm btn-dark">Ver más</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Paginación --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $eventos->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection
