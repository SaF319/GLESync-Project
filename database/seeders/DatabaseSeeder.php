<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategoriasSeeder::class,
            EventosDefaultSeeder::class,  // PRIMERO - crea ID: 1
            RootUserSeeder::class,        // SEGUNDO - crea ID: 2
        ]);
    }
}
