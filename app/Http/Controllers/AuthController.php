<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\AuthManagerSingleton;
use App\Models\Usuarios;

class AuthController extends Controller
{
    public function validacion(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'El correo es obligatorio',
            'email.email' => 'Ingrese un correo válido',
            'password.required' => 'Ingrese su contraseña',
        ]);
        
        $auth = AuthManagerSingleton::getInstance();
        $usuario = $auth->login($request->email, $request->password);

        if (!$usuario) {
            return back()->withErrors(['login' => 'Credenciales incorrectas'])->withInput();
        }

        if ($usuario->is_2fa_enabled && $usuario->google2fa_secret) {
            session(['2fa:user_id' => $usuario->id]);
            return redirect()->route('2fa.index');
        }

        Auth::login($usuario);
        $request->session()->regenerate();
        return redirect()->route('dashboard')->with('success', 'Bienvenido ' . $usuario->nombre);
    }

    public function registro(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $usuario = Usuarios::create([
            'nombre' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($usuario);

        $usuario->load([
            'organizador.eventos' => function ($query) {
                $query->with(['categorias', 'fechasHoras', 'imagen']);
            }
        ]);

        if ($request->has('enable_2fa')) {
            return redirect()->route('2fa.setup')->with('success', 'Completa la activación del 2FA.');
        }

        return redirect()->route('dashboard')->with('success', 'Cuenta creada correctamente. Bienvenido ' . $usuario->nombre);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Has cerrado sesión.');
    }
}
