@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Conviértete en Organizador</h2>
    <p>Para crear un evento, primero necesitas registrarte como organizador.</p>

    <form action="{{ route('organizador.hacerse') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="contacto" class="form-label">Contacto</label>
            <input type="text" name="contacto" id="contacto" class="form-control"
                    value="{{ old('contacto', Auth::user()->email) }}">
        </div>

        <button type="submit" class="btn btn-primary">Registrarme como Organizador</button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
