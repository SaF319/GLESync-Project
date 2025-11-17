<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuarios;
use App\Models\Organizador;
use App\Models\Imagen;
use App\Models\Categoria;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventosDefaultSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $this->command->info('🎯 INICIANDO SEEDER CON PROCEDIMIENTO ALMACENADO');

            // ✅ Usar firstOrCreate para evitar duplicados
            $usuario = Usuarios::firstOrCreate(
                ['email' => 'organizador@planazo.com'],
                [
                    'nombre' => 'Organizador Planazo',
                    'password' => Hash::make('12345678'),
                    'es_root' => false,
                ]
            );

            $organizador = Organizador::firstOrCreate(
                ['usuario_id' => $usuario->id],
                ['contacto' => '099123456']
            );

            $this->command->info("✅ Usuario: {$usuario->id}, Organizador: {$organizador->id}");

            // ✅ Eventos data - USANDO EL PROCEDIMIENTO ALMACENADO
            $eventosDefault = [
                [
                    'titulo' => 'Recital de Rock',
                    'descripcion' => 'Concierto en el estadio este viernes.',
                    'categoria' => 'Conciertos',
                    'latitud' => -34.8984,
                    'longitud' => -56.17842,
                    'imagen' => ['nombre' => 'recitales.jpg', 'ruta' => 'imagenes/recitales.jpg'],
                ],
                [
                    'titulo' => 'Campeonato de Fútbol',
                    'descripcion' => 'Partido final del torneo local.',
                    'categoria' => 'Deportes',
                    'latitud' => -34.89455,
                    'longitud' => -56.1528,
                    'imagen' => ['nombre' => 'football.jpg', 'ruta' => 'imagenes/football.jpg'],
                ],
                [
                    'titulo' => 'Muestra de Arte',
                    'descripcion' => 'Exposición de artistas locales en el museo.',
                    'categoria' => 'Exposiciones',
                    'latitud' => -34.90597,
                    'longitud' => -56.19417,
                    'imagen' => ['nombre' => 'muestraArte.jpg', 'ruta' => 'imagenes/muestraArte.jpg'],
                ],
            ];

            $eventosCreados = 0;

            foreach ($eventosDefault as $data) {
                try {
                    $this->command->info("🎯 Creando evento via procedimiento: {$data['titulo']}");

                    // 🐛 DEBUG: mostrar parámetros que se enviarán al procedimiento
                    $fechaHora = Carbon::now()->addDays(7)->setTime(20, 0, 0);
                    $this->command->info("🔍 PARÁMETROS DEBUG:");
                    $this->command->info("   - Título: {$data['titulo']}");
                    $this->command->info("   - Descripción: " . substr($data['descripcion'], 0, 50) . '...');
                    $this->command->info("   - Fecha/Hora: {$fechaHora}");
                    $this->command->info("   - Latitud: {$data['latitud']}");
                    $this->command->info("   - Longitud: {$data['longitud']}");
                    $this->command->info("   - Organizador ID: {$organizador->id}");

                    // ✅ USAR EL PROCEDIMIENTO ALMACENADO crear_evento
                    DB::statement('CALL crear_evento(?, ?, ?, ?, ?, ?)', [
                        $data['titulo'],
                        $data['descripcion'],
                        $fechaHora, // fecha_hora
                        $data['latitud'],
                        $data['longitud'],
                        $organizador->id
                    ]);

                    // 🐛 DEBUG: verificar si el evento se creó en la BD
                    $eventoReciente = DB::table('eventos')
                        ->where('organizador_id', $organizador->id)
                        ->where('titulo', $data['titulo'])
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($eventoReciente) {
                        $this->command->info("✅ Evento encontrado en BD - ID: {$eventoReciente->id}");

                        // 🐛 DEBUG: verificar si la fecha_hora también se creó
                        $fechaCreada = DB::table('fechas_horas')
                            ->where('evento_id', $eventoReciente->id)
                            ->first();

                        if ($fechaCreada) {
                            $this->command->info("✅ Fecha/Hora creada en BD: {$fechaCreada->fecha_hora}");
                        } else {
                            $this->command->warn("⚠️ Fecha/Hora NO se creó en fechas_horas");
                        }
                    } else {
                        $this->command->error("❌ Evento NO se encontró en la tabla eventos");
                        continue;
                    }

                    // ✅ Obtener el ID del último evento insertado
                    $eventoId = DB::getPdo()->lastInsertId();
                    $this->command->info("🔍 lastInsertId() retornó: " . ($eventoId ?: 'NULL'));

                    // Si lastInsertId() no funciona, usar el ID del evento encontrado
                    if (!$eventoId) {
                        $eventoId = $eventoReciente->id;
                        $this->command->info("🔍 Usando ID alternativo: {$eventoId}");
                    }

                    $this->command->info("✅ Evento creado via procedimiento - ID: {$eventoId}");

                    // ✅ Crear imagen asociada
                    try {
                        Imagen::create([
                            'evento_id' => $eventoId,
                            'nombre' => $data['imagen']['nombre'],
                            'ruta' => $data['imagen']['ruta'],
                        ]);
                        $this->command->info("✅ Imagen creada correctamente");
                    } catch (\Exception $e) {
                        $this->command->error("❌ Error creando imagen: " . $e->getMessage());
                    }

                    // ✅ Asociar categoría
                    try {
                        $categoria = Categoria::firstOrCreate(['nombre' => $data['categoria']]);
                        DB::table('categoria_evento')->insert([
                            'evento_id' => $eventoId,
                            'categoria_id' => $categoria->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $this->command->info("✅ Categoría asociada: {$categoria->nombre}");
                    } catch (\Exception $e) {
                        $this->command->error("❌ Error asociando categoría: " . $e->getMessage());
                    }

                    $eventosCreados++;
                    $this->command->info("✅ Completado: {$data['titulo']}");
                    $this->command->info("----------------------------------------");

                } catch (\Exception $e) {
                    $this->command->error("❌ Error creando evento '{$data['titulo']}': " . $e->getMessage());
                    $this->command->error("🔍 Stack trace: " . $e->getTraceAsString());
                }
            }

            $this->command->info("🎉 Eventos creados via procedimiento: {$eventosCreados}");

        } catch (\Exception $e) {
            $this->command->error('💥 ERROR: ' . $e->getMessage());
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
