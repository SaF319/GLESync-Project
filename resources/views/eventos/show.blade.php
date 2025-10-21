@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Evento {{ $evento->titulo }}</h2>

    {{-- Imagen --}}
    <div class="mb-3">
        @if($evento->imagen)
            <img src="{{ asset($evento->imagen->ruta) }}" class="card-img-top" alt="{{ $evento->titulo }}" style="height: 400px; object-fit: cover;">
        @else
            <h3>Imagen no disponible</h3>
        @endif
    </div>

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

    {{-- Descripción --}}
    <div class="mb-3">
        <h3>Descripción</h3>
        <p>{{ $evento->descripcion }}</p>
    </div>

    {{-- Fecha y Hora --}}
    <div class="mb-3">
        <h3>Hora/s del Evento</h3>
        <ul>
            <li>
                {{ optional($evento->fechasHoras->first())->fecha_hora
                    ? $evento->fechasHoras->first()->fecha_hora->format('Y-m-d H:i')
                    : 'Fecha no disponible'
                }}
            </li>
        </ul>
    </div>

    {{-- Categorías --}}
    <div class="mb-3">
        <h2>Categorías</h2>
        <h3>{{ $evento->categorias->pluck('nombre')->implode(', ') }}</h3>
    </div>

    {{-- Botón volver al Dashboard --}}
    <div class="mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-dark">
            ← Volver al Dashboard
        </a>
    </div>
</div>
@endsection
