@extends('layouts.app')

@section('title', 'Resultados de Búsqueda')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <form action="{{ route('home.buscar') }}" method="GET">
                <div class="input-group">
                    <input type="text"
                           name="q"
                           class="form-control form-control-lg"
                           placeholder="Buscar eventos..."
                           value="{{ $terminoBusqueda }}"
                           required>
                    <button class="btn btn-primary btn-lg" type="submit">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver al inicio
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-info-circle"></i>
                        <strong>Resultados para:</strong> "{{ $terminoBusqueda }}"
                        @if($usuarioAutenticado)
                            <span class="badge bg-primary ms-2">Tus eventos</span>
                        @else
                            <span class="badge bg-secondary ms-2">Todos los eventos</span>
                        @endif
                    </div>
                    <span class="badge bg-success fs-6">{{ $totalResultados }} eventos encontrados</span>
                </div>
            </div>
        </div>
    </div>

    @if($eventos->isEmpty())
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="text-muted mb-3">
                            <i class="fas fa-search fa-3x"></i>
                        </div>
                        <h4>No se encontraron eventos</h4>
                        <p class="text-muted mb-4">No hay eventos que coincidan con "{{ $terminoBusqueda }}"</p>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-home"></i> Volver al inicio
                        </a>
                        <a href="{{ route('home.buscar') }}" class="btn btn-outline-primary">
                            <i class="fas fa-redo"></i> Nueva búsqueda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($eventos as $evento)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if(isset($evento->imagen_url) || (is_array($evento) && isset($evento['imagen_url'])))
                            <img src="{{ is_array($evento) ? $evento['imagen_url'] : $evento->imagen_url }}"
                                 class="card-img-top"
                                 alt="{{ is_array($evento) ? $evento['titulo'] : $evento->titulo }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                 style="height: 200px;">
                                <i class="fas fa-calendar-alt fa-3x text-muted"></i>
                            </div>
                        @endif

                        <div class="card-body">
                            <h6 class="card-title">
                                {{ is_array($evento) ? $evento['titulo'] : $evento->titulo }}
                                @if(isset($evento->relevancia) && $evento->relevancia >= 3)
                                    <span class="badge bg-warning ms-2">Alta relevancia</span>
                                @endif
                            </h6>

                            <p class="card-text text-muted small">
                                {{ Str::limit(is_array($evento) ? $evento['descripcion'] : $evento->descripcion, 100) }}
                            </p>

                            <div class="evento-info">
                                @if(isset($evento->fechas_evento) || (is_array($evento) && isset($evento['fechas_evento'])))
                                    <p class="mb-1">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i>
                                            {{ is_array($evento) ? $evento['fechas_evento'] : $evento->fechas_evento }}
                                        </small>
                                    </p>
                                @endif

                                @if(isset($evento->categorias) || (is_array($evento) && isset($evento['categorias'])))
                                    <p class="mb-1">
                                        <small class="text-muted">
                                            <i class="fas fa-tag"></i>
                                            {{ is_array($evento) ? $evento['categorias'] : $evento->categorias }}
                                        </small>
                                    </p>
                                @endif

                                @if(isset($evento->total_comentarios))
                                    <p class="mb-0">
                                        <small class="text-muted">
                                            <i class="fas fa-comments"></i>
                                            {{ $evento->total_comentarios }} comentarios
                                        </small>
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge
                                    @if((is_array($evento) ? $evento['estado'] : $evento->estado) === 'futuro') bg-success
                                    @elseif((is_array($evento) ? $evento['estado'] : $evento->estado) === 'presente') bg-warning
                                    @else bg-secondary @endif">
                                    {{ is_array($evento) ? $evento['estado'] : $evento->estado }}
                                </span>

                                @if(isset($evento->id))
                                    <a href="{{ route('eventos.show', is_array($evento) ? $evento['id'] : $evento->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        Ver detalles
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($totalPaginas > 1)
            <div class="row mt-4">
                <div class="col-12">
                    <nav>
                        <ul class="pagination justify-content-center">
                            @for($i = 1; $i <= $totalPaginas; $i++)
                                <li class="page-item {{ $i == $paginaActual ? 'active' : '' }}">
                                    <a class="page-link"
                                        href="{{ route('home.buscar', ['q' => $terminoBusqueda, 'page' => $i]) }}">
                                        {{ $i }}
                                    </a>
                                </li>
                            @endfor
                        </ul>
                    </nav>
                </div>
            </div>
        @endif
    @endif
</div>

<style>
.evento-info p {
    margin-bottom: 0.3rem;
}
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-5px);
}
</style>
@endsection
