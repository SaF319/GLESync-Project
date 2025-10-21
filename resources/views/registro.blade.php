@extends('layouts.app')

@section('content')
<div class="row justify-content-center" style="min-height:70vh; align-items:center;">
    <div class="col-md-6">
        <h2 class="text-center mb-4">Crear cuenta en Planazo 🎵</h2>
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

                {{-- Formulario de Registro --}}
                <form action="{{ route('register.process') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre completo</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Tu nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="tu@correo.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="••••••••" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Registrarse</button>
                </form>

                <div class="mt-3 text-center">
                    <small>¿Ya tienes cuenta? <a href="{{ url('/login') }}">Inicia sesión</a></small>
                </div>

                {{-- Botón volver a home --}}
                <div class="mt-3 text-center">
                    <a href="{{ url('/') }}" class="btn btn-outline-dark w-50">Volver a Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
