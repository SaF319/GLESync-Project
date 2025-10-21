<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuarios;

class AuthManagerSingleton
{
    // 🔒 Instancia única
    private static $instance = null;

    // 🔒 Constructor privado: evita instanciar directamente
    private function __construct() {}

    // ✅ Obtener la instancia única
    public static function getInstance(): AuthManagerSingleton
    {
        if (self::$instance === null) {
            self::$instance = new AuthManagerSingleton();
        }
        return self::$instance;
    }

    // 🔐 Método de login encapsulado
    public function login($email, $password)
    {
        $usuario = Usuarios::where('email', $email)->first();

        if (!$usuario || !Hash::check($password, $usuario->password)) {
            return null; // Login fallido
        }

        Auth::login($usuario);
        return $usuario;
    }
}
