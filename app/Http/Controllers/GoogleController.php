<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $usuario = Usuarios::where('email', $googleUser->getEmail())->first();

            if (!$usuario) {
                $usuario = Usuarios::create([
                    'nombre' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(uniqid()),
                ]);
            }

            if ($usuario->is_2fa_enabled && $usuario->google2fa_secret) {
                session(['2fa:user_id' => $usuario->id]);
                return redirect()->route('2fa.index');
            }

            Auth::login($usuario);
            return redirect()->route('dashboard')->with('success', 'Bienvenido ' . $usuario->nombre);

        } catch (\Throwable $e) {
            Log::error('Error Google Login: ' . $e->getMessage());

            return redirect('/login')->withErrors([
                'login' => 'Error al autenticar con Google'
            ]);
        }
    }
}
