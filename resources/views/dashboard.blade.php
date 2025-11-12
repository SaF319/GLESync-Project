@extends('layouts.app')

@section('content')
@php
    $eventos = $eventos ?? collect();
@endphp

<div class="container py-4">
    {{-- Header del Dashboard --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h2 class="mb-3">Dashboard 🎉</h2>
                    <h4 class="mb-3">Hola, <strong>{{ Auth::user()->nombre }}</strong></h4>
                    <p class="text-muted mb-3">Bienvenido a tu panel de control de Planazo</p>

                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('eventos.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Crear Nuevo Evento
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sección de Gestión de Eventos --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event"></i> Mis Eventos
                    </h5>
                </div>
                <div class="card-body">
                    {{-- Alertas --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Lista de Eventos --}}
                    @if($eventos->count() > 0)
                        <div class="row">
                            @foreach($eventos as $evento)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        @if($evento->imagen)
                                            <img src="{{ asset($evento->imagen->ruta) }}" class="card-img-top" alt="{{ $evento->titulo }}" style="height: 200px; object-fit: cover;">
                                        @else
                                            <div class="card h-100 shadow-sm card-img-top" style="height: 100px; object-fit: cover;">
                                                <img src="{{ isset($evento['imagen']['ruta']) ? asset($evento['imagen']['ruta']) : asset('imagenes/no_image.png') }}" class="d-block w-100" alt="Evento">
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <h6 class="card-title">{{ Str::limit($evento->titulo, 50) }}</h6>
                                            <p class="card-text small text-muted">
                                                <strong>Fecha:</strong>
                                                @if($evento->fechasHoras->first())
                                                    {{ \Carbon\Carbon::parse($evento->fechasHoras->first()->fecha_hora)->format('d/m/Y H:i') }}
                                                @else
                                                    Sin fecha definida
                                                @endif
                                            </p>
                                            <p class="card-text small">
                                                <strong>Categorías:</strong>
                                                {{ $evento->categorias->pluck('nombre')->implode(', ') }}
                                            </p>
                                        </div>
                                        <div class="card-footer bg-white">
                                            <div class="btn-group w-100">
                                                <a href="{{ route('eventos.show', $evento->id) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-eye"></i> Ver
                                                </a>
                                                <a href="{{ route('eventos.edit', $evento->id) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil"></i> Editar
                                                </a>
                                                <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('¿Estás seguro de eliminar este evento?')">
                                                        <i class="bi bi-trash"></i> Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x" style="font-size: 3rem; color: #6c757d;"></i>
                            <h5 class="mt-3 text-muted">No tienes eventos creados</h5>
                            <p class="text-muted">Comienza creando tu primer evento</p>
                            <a href="{{ route('eventos.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Crear Primer Evento
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Estadísticas rápidas --}}
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h3 class="text-primary">{{ $eventos->count() }}</h3>
                    <p class="text-muted mb-0">Eventos Totales</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h3 class="text-success">
                        {{ $eventos->filter(fn($evento) => $evento->fechasHoras->count() > 0)->count() }}
                    </h3>
                    <p class="text-muted mb-0">Eventos Programados</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h3 class="text-info">
                        {{ $eventos->filter(fn($evento) => $evento->imagen)->count() }}
                    </h3>
                    <p class="text-muted mb-0">Con Imágenes</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
    .card {
        transition: transform 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-2px);
    }
    .btn-group .btn {
        flex: 1;
    }
</style>
@endsection
