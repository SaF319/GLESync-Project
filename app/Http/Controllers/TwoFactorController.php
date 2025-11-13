<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuarios;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    public function index()
    {
        $userId = session('2fa:user_id');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['login' => 'Sesión expirada. Inicia sesión de nuevo.']);
        }

        $user = Usuarios::find($userId);
        if (!$user || !$user->is_2fa_enabled || !$user->google2fa_secret) {
            session()->forget('2fa:user_id');
            return redirect()->route('login')->withErrors(['login' => 'Error: Usuario no tiene 2FA configurado.']);
        }

        return view('auth.2fa');
    }

    public function setup()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $google2fa = new Google2FA();

        if (!$user->google2fa_secret) {
            $user->google2fa_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        $qrUrl = $google2fa->getQRCodeUrl(
            'PLANAZO',
            $user->email,
            $user->google2fa_secret
        );

        return view('auth.2fa_setup', [
            'qrUrl' => $qrUrl,
            'secret' => $user->google2fa_secret,
        ]);
    }

    public function enable(Request $request)
    {
        $request->validate(['one_time_password' => 'required|numeric']);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->input('one_time_password'));

        if ($valid) {
            $user->is_2fa_enabled = true;
            $user->save();
            return redirect()->route('dashboard')->with('success', '2FA activado correctamente.');
        }

        return back()->withErrors(['one_time_password' => 'Código inválido.']);
    }

    public function verify(Request $request)
    {
        $request->validate(['one_time_password' => 'required|numeric']);

        $userId = session('2fa:user_id');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['login' => 'Sesión expirada. Inicia sesión de nuevo.']);
        }

        $user = Usuarios::find($userId);
        if (!$user || !$user->is_2fa_enabled || !$user->google2fa_secret) {
            session()->forget('2fa:user_id');
            return redirect()->route('login')->withErrors(['login' => 'Error: Usuario no tiene 2FA configurado.']);
        }

        $google2fa = new Google2FA();
        if ($google2fa->verifyKey($user->google2fa_secret, $request->input('one_time_password'))) {
            Auth::login($user);
            session()->forget('2fa:user_id');
            return redirect()->route('dashboard')->with('success', 'Bienvenido ' . $user->nombre);
        }

        return back()->withErrors(['one_time_password' => 'Código incorrecto.']);
    }
}
