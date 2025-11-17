@extends('layouts.app')

@section('title', 'Panel Root')

@section('content')
<div class="container">
    <h1 class="mb-4">Panel Root</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card p-3 mb-3">
                <h5>Usuarios</h5>
                <p>Total: {{ \App\Models\Usuarios::count() }}</p>
                <a href="{{ route('admin.usuarios') }}" class="btn btn-primary btn-sm">Ver usuarios</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 mb-3">
                <h5>Comentarios</h5>
                <p>Total: {{ \App\Models\Comentarios::count() }}</p>
                <a href="{{ route('admin.comentarios') }}" class="btn btn-primary btn-sm">Ver comentarios</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 mb-3">
                <h5>Eventos</h5>
                <p>Total: {{ \App\Models\Evento::count() }}</p>
                <a href="{{ route('admin.eventos') }}" class="btn btn-primary btn-sm">Ver eventos</a>
            </div>
        </div>
    </div>
</div>
@endsection
