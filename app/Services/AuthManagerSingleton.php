<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Models\Usuarios;

class AuthManagerSingleton
{
    private static $instance = null;

    private function __construct() {}

    public static function getInstance(): AuthManagerSingleton
    {
        if (self::$instance === null) {
            self::$instance = new AuthManagerSingleton();
        }
        return self::$instance;
    }

    public function login(string $email, string $password): ?Usuarios
    {
        $usuario = Usuarios::where('email', $email)->first();

        if (!$usuario || !Hash::check($password, $usuario->password)) {
            return null;
        }

        return $usuario;
    }
}
