@extends('layouts.app')

@section('title', 'Eventos')

@section('content')
<div class="container">
    <h1 class="mb-4">Eventos</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Título</th>
                <th>Organizador</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($eventos as $evento)
            <tr @if($evento->trashed()) class="table-danger" @endif>
                <td>{{ $evento->titulo }}</td>
                <td>{{ $evento->organizador->usuario->nombre ?? 'Desconocido' }}</td>
                <td>{{ $evento->fecha->format('d/m/Y') ?? 'Sin fecha' }}</td>
                <td>{{ $evento->trashed() ? 'Eliminado' : 'Activo' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
