@extends('layouts.app')

@section('content')
<div class="row justify-content-center" style="min-height:70vh; align-items:center;">
    <div class="col-md-6">
        <h2 class="text-center mb-4">Crear cuenta en Planazo 🎵</h2>
        <div class="card shadow-sm">
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre completo</label>
                        <input type="text" class="form-control" id="nombre" placeholder="Tu nombre">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" placeholder="tu@correo.com">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" placeholder="••••••••">
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                        <input type="password" class="form-control" id="password_confirmation" placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Registrarse</button>
                </form>

                <hr>

                <div class="d-grid gap-2">
                    <button class="btn btn-google">Registrarse con Google</button>
                    <button class="btn btn-facebook">Registrarse con Facebook</button>
                </div>

                <div class="mt-3 text-center">
                    <small>¿Ya tienes cuenta? <a href="{{ url('/login') }}">Inicia sesión aquí</a></small>
                </div>

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
