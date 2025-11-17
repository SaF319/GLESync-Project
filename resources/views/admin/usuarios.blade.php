@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="container">
    <h1 class="mb-4">Usuarios registrados</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Baneado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->nombre }}</td>
                <td>{{ $usuario->email }}</td>
                <td>{{ $usuario->baneado ? 'Sí' : 'No' }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.usuarios.baneo', $usuario->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-{{ $usuario->baneado ? 'success' : 'danger' }}">
                            {{ $usuario->baneado ? 'Desbanear' : 'Banear' }}
                        </button>
                    </form>
                    <a href="{{ route('admin.usuarios.comentarios', $usuario->id) }}" class="btn btn-sm btn-info mt-1">Ver comentarios</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
