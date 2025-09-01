<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Usuarios;

class AuthController extends Controller
{
    public function validacion(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.email' => 'Ingrese un correo válido',
            'password.required' => 'Ingrese su contraseña',
        ]);

        $usuario = Usuarios::where('email', $request->email)->first();

        if ($usuario && Hash::check($request->password, $usuario->password)) {
            Auth::login($usuario);
            return redirect()->route('dashboard')->with('success', 'Bienvenido ' . $usuario->nombre);
        } else {
            return back()->withErrors(['login' => 'Credenciales incorrectas'])->withInput();
        }
    }

    public function registro(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|confirmed|min:6',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'email.required' => 'El correo es obligatorio',
            'email.email' => 'Ingrese un correo válido',
            'email.unique' => 'Este correo ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.confirmed' => 'La confirmación no coincide',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        ]);

        $usuario = Usuarios::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($usuario);

        return redirect()->route('home')->with('success', 'Cuenta creada correctamente. Bienvenido ' . $usuario->nombre);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form')->with('success', 'Has cerrado sesión.');
    }
}
