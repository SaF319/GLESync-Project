<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\AuthManagerSingleton;
use App\Models\Usuarios;
use App\Models\Organizador;

class AuthController extends Controller
{
    /**
     * Iniciar sesión (login)
     */
    public function validacion(Request $request)
    {
        // Validación de datos
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'El correo es obligatorio',
            'email.email' => 'Ingrese un correo válido',
            'password.required' => 'Ingrese su contraseña',
        ]);

        // ✅ Usamos el Singleton para manejar el login
        $auth = AuthManagerSingleton::getInstance();
        $usuario = $auth->login($request->email, $request->password);

        // Si no se autentica correctamente
        if (!$usuario) {
            return back()->withErrors(['login' => 'Credenciales incorrectas'])->withInput();
        }

        // Regenerar sesión
        $request->session()->regenerate();

        // ⚡ Cargar organizador y eventos con relaciones para dashboard
        $usuario->load([
            'organizador.eventos' => function ($query) {
                $query->with(['categorias', 'fechasHoras', 'imagen']);
            }
        ]);

        // Redirigir al dashboard
        return redirect()->route('dashboard')->with('success', 'Bienvenido ' . $usuario->nombre);
    }

    /**
     * Registro de nuevo usuario
     */
    public function registro(Request $request)
    {
        // Validar datos de registro
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|confirmed|min:6',
        ], [
            'name.required' => 'El nombre es obligatorio',
            'email.required' => 'El correo es obligatorio',
            'email.email' => 'Ingrese un correo válido',
            'email.unique' => 'Este correo ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.confirmed' => 'La confirmación no coincide',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        ]);

        // Crear nuevo usuario
        $usuario = Usuarios::create([
            'nombre' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Autologin usando el Singleton
        $auth = AuthManagerSingleton::getInstance();
        Auth::login($usuario);

        // Cargar posibles relaciones del nuevo usuario
        $usuario->load([
            'organizador.eventos' => function ($query) {
                $query->with(['categorias', 'fechasHoras', 'imagen']);
            }
        ]);

        return redirect()->route('dashboard')->with('success', 'Cuenta creada correctamente. Bienvenido ' . $usuario->nombre);
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Has cerrado sesión.');
    }
}
