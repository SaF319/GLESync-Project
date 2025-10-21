@extends('layouts.app')

@section('content')
<div class="row justify-content-center" style="min-height:70vh; align-items:center;">
    <div class="col-md-5">
        <h2 class="text-center mb-4">Bienvenido a Planazo 🎵</h2>
        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Mensajes de error --}}
                @if($errors->any())
                    @foreach($errors->all() as $error)
                        <p style="color:red;">{{ $error }}</p>
                    @endforeach
                @endif

                {{-- Mensaje de éxito --}}
                @if(session('success'))
                    <p style="color:green;">{{ session('success') }}</p>
                @endif


                {{-- Formulario de Login --}}
                <form action="{{ route('login.process') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="tu@correo.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Ingresar</button>
                </form>

                <hr>

                {{-- Botones sociales (decorativos) --}}
                <div class="d-grid gap-2">
                    <button class="btn btn-google">Iniciar con Google</button>
                    <button class="btn btn-facebook">Iniciar con Facebook</button>
                </div>

                <div class="mt-3 text-center">
                    <small>¿No tienes cuenta? <a href="{{ url('/registro') }}">Regístrate aquí</a></small>
                </div>

                {{-- Botón volver a home --}}
                <div class="mt-3 text-center">
                    <a href="{{ url('/') }}" class="btn btn-outline-dark w-50">Volver a Home</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-google {
        background-color: #db4437;
        color: white;
    }
    .btn-google:hover {
        background-color: #c33d30;
        color: white;
    }
    .btn-facebook {
        background-color: #1877f2;
        color: white;
    }
    .btn-facebook:hover {
        background-color: #145dbf;
        color: white;
    }
</style>
@endsection
