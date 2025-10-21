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
                <img src="{{ asset($evento->imagen->ruta) }}" alt="Imagen Evento" class="mt-2" style="max-width:200px;">
            @endif
        </div>

        <button type="submit" class="btn btn-dark">Actualizar Evento</button>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancelar</a>
    </form>
</div>
@endsection
