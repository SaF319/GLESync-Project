<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuarios;
use App\Models\Organizador;
use App\Models\Evento;
use App\Models\Imagen;
use App\Models\FechaHora;
use App\Models\Categoria;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EventosDefaultSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $this->command->info('🔍 Iniciando EventosDefaultSeeder (PRIMERO - IDs 1)...');

            // ✅ FORZAR IDs específicos para mantener consistencia
            DB::statement('ALTER TABLE usuarios AUTO_INCREMENT = 1;');
            DB::statement('ALTER TABLE organizadores AUTO_INCREMENT = 1;');
            DB::statement('ALTER TABLE eventos AUTO_INCREMENT = 1;');

            // ✅ Crear usuario organizador con ID: 1
            $usuario = Usuarios::create([
                'nombre' => 'Organizador Planazo',
                'email' => 'organizador@planazo.com',
                'password' => Hash::make('12345678'),
                'es_root' => false,
            ]);

            $this->command->info("✅ Usuario Organizador creado: ID {$usuario->id}");

            // ✅ Crear organizador con ID: 1
            $organizador = Organizador::create([
                'usuario_id' => $usuario->id,
                'contacto' => '099123456'
            ]);

            $this->command->info("✅ Organizador creado: ID {$organizador->id}");

            // ✅ Lista de eventos por defecto
            $eventosDefault = [
                [
                    'titulo' => 'Recital de Rock',
                    'descripcion' => 'Concierto en el estadio este viernes.',
                    'categoria' => 'Conciertos',
                    'latitud' => -34.8984,
                    'longitud' => -56.17842,
                    'imagen' => [
                        'nombre' => 'recitales.jpg',
                        'ruta'   => 'imagenes/recitales.jpg',
                    ],
                ],
                [
                    'titulo' => 'Campeonato de Fútbol',
                    'descripcion' => 'Partido final del torneo local.',
                    'categoria' => 'Deportes',
                    'latitud' => -34.89455,
                    'longitud' => -56.1528,
                    'imagen' => [
                        'nombre' => 'football.jpg',
                        'ruta'   => 'imagenes/football.jpg',
                    ],
                ],
                [
                    'titulo' => 'Muestra de Arte',
                    'descripcion' => 'Exposición de artistas locales en el museo.',
                    'categoria' => 'Exposiciones',
                    'latitud' => -34.90597,
                    'longitud' => -56.19417,
                    'imagen' => [
                        'nombre' => 'muestraArte.jpg',
                        'ruta'   => 'imagenes/muestraArte.jpg',
                    ],
                ],
            ];

            $eventosCreados = 0;

            foreach ($eventosDefault as $data) {
                try {
                    // CREAR evento
                    $evento = Evento::create([
                        'titulo' => $data['titulo'],
                        'descripcion' => $data['descripcion'],
                        'organizador_id' => $organizador->id,
                        'latitud' => $data['latitud'],
                        'longitud' => $data['longitud'],
                    ]);

                    $eventosCreados++;

                    // CREAR imagen asociada
                    Imagen::create([
                        'evento_id' => $evento->id,
                        'nombre' => $data['imagen']['nombre'],
                        'ruta'   => $data['imagen']['ruta'],
                    ]);

                    // CREAR fecha por defecto
                    FechaHora::create([
                        'evento_id' => $evento->id,
                        'fecha_hora' => Carbon::now()->addDays(7)->setTime(20, 0, 0),
                    ]);

                    // ASOCIAR categoría
                    $categoria = Categoria::firstOrCreate(['nombre' => $data['categoria']]);
                    $evento->categorias()->syncWithoutDetaching([$categoria->id]);

                    $this->command->info("✅ Evento creado: ID {$evento->id} - {$evento->titulo}");

                } catch (\Exception $e) {
                    $this->command->error("❌ Error creando evento: " . $e->getMessage());
                }
            }

            $this->command->info("🎉 EventosDefaultSeeder completado. Eventos creados: {$eventosCreados}");

        } catch (\Exception $e) {
            $this->command->error('💥 ERROR: ' . $e->getMessage());
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
