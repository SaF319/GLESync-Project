@extends('layouts.app')

@section('content')
<div class="container" style="min-height:70vh; display:flex; justify-content:center; align-items:center;">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h2 class="mb-4">Bienvenido 🎉</h2>

                @auth
                    <h4 class="mb-3">Hola, <strong>{{ Auth::user()->name }}</strong></h4>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger w-50">Cerrar sesión</button>
                    </form>
                @else
                    <p>No has iniciado sesión.</p>
                    <a href="{{ route('login') }}" class="btn btn-dark">Inicia sesión</a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
