<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RootUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $this->command->info('🔍 Iniciando RootUserSeeder (SEGUNDO - IDs 2)...');

            // ✅ FORZAR que el próximo ID sea 2
            DB::statement('ALTER TABLE usuarios AUTO_INCREMENT = 2;');

            // Verificar si ya existe el usuario root
            $root = Usuarios::where('email', 'root@gle.com')->first();

            if (!$root) {
                Usuarios::create([
                    'nombre' => 'Root',
                    'email' => 'root@gle.com',
                    'password' => Hash::make('root12345'),
                    'es_root' => true,
                    'is_2fa_enabled' => false,
                    'google2fa_secret' => null,
                    'baneado' => false,
                ]);
                $this->command->info('✅ Usuario root creado correctamente (ID: 2)');
            } else {
                $this->command->info('ℹ️  Usuario root ya existe.');
            }

        } catch (\Exception $e) {
            $this->command->error('❌ Error creando usuario root: ' . $e->getMessage());
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
