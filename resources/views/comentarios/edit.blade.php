@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Editar Comentario</h2>

    {{-- Mensajes de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('comentario.update', $comentario->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="comentario" class="form-label">Tu comentario</label>
            <textarea name="comentario" id="comentario" class="form-control" rows="4" required>{{ $comentario->comentario }}</textarea>
        </div>

        {{-- Botones --}}
        <button type="submit" class="btn btn-primary">Actualizar</button>

        {{-- Cancelar vuelve al SHOW DEL EVENTO --}}
        <a href="{{ route('eventos.show', $comentario->evento_id) }}" class="btn btn-secondary">
            Cancelar
        </a>

    </form>

</div>
@endsection
