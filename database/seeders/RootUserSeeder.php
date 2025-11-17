<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;

class RootUserSeeder extends Seeder
{
    public function run(): void
    {
        // Si ya existe un usuario root, no hacemos nada
        $root = Usuarios::where('es_root', 1)->first();

        if (!$root) {
            Usuarios::create([
                'nombre' => 'Root',
                'email' => 'root@gle.com',
                'password' => Hash::make('root12345'),
                'es_root' => 1,
                'is_2fa_enabled' => 0,
                'google2fa_secret' => null,
                'baneado' => 0,
            ]);
        }
    }
}
