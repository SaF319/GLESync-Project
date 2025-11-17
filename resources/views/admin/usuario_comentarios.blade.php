@extends('layouts.app')

@section('title', 'Comentarios de ' . $usuario->nombre)

@section('content')
<div class="container">
    <h1 class="mb-4">Comentarios de {{ $usuario->nombre }}</h1>

    <a href="{{ route('admin.usuarios') }}" class="btn btn-secondary mb-3">Volver a usuarios</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Evento</th>
                <th>Contenido</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comentarios as $comentario)
            <tr @if($comentario->trashed()) class="table-danger" @endif>
                <td>{{ $comentario->evento->titulo ?? 'Evento eliminado' }}</td>
                <td>{{ $comentario->contenido }}</td>
                <td>{{ $comentario->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
