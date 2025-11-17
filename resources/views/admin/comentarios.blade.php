@extends('layouts.app')

@section('title', 'Comentarios')

@section('content')
<div class="container">
    <h1 class="mb-4">Comentarios</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Evento</th>
                <th>Contenido</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comentarios as $comentario)
            <tr @if($comentario->trashed()) class="table-danger" @endif>
                <td>{{ $comentario->usuario->nombre ?? 'Desconocido' }}</td>
                <td>{{ $comentario->evento->titulo ?? 'Evento eliminado' }}</td>
                <td>{{ $comentario->contenido }}</td>
                <td>{{ $comentario->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    @if($comentario->trashed())
                    <form method="POST" action="{{ route('admin.comentarios.restore', $comentario->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Restaurar</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
