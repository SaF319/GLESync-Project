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

        <button type="submit" class="btn btn-primary">Crear Evento</button>
        <a href="{{ route('eventos.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection
