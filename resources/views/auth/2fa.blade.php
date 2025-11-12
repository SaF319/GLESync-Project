@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <h2>Verificación en dos pasos</h2>
    <p>Ingresa el código generado por tu app de autenticación (Google Authenticator o similar)</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('2fa.verify') }}" method="POST" class="mt-4">
        @csrf
        <input type="text" name="one_time_password" class="form-control text-center"
               placeholder="Código 2FA" maxlength="6" required>
        <button type="submit" class="btn btn-dark mt-3">Verificar</button>
    </form>
</div>
@endsection
