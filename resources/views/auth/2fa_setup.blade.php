@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <h2>Activar verificación en dos pasos</h2>
    <p>Escaneá este código QR con Google Authenticator o Authy.</p>

    <div class="mt-4">
        <div class="d-flex justify-content-center mb-3">
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->format('svg')->generate($qrUrl) !!}
        </div>
        <p class="mt-3">O usá esta clave manualmente: <strong>{{ $secret }}</strong></p>
    </div>

    <form action="{{ route('2fa.enable') }}" method="POST" class="mt-4">
        @csrf
        <label for="otp">Ingresá el código de tu app 2FA:</label>
        <input type="text" name="one_time_password" class="form-control text-center" placeholder="Código 6 dígitos" required>
        <button type="submit" class="btn btn-success mt-3">Activar</button>
    </form>
</div>
@endsection
