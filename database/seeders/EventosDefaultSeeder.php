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

class EventosDefaultSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Crear usuario por defecto
        $usuario = Usuarios::firstOrCreate(
            ['email' => 'organizador@planazo.com'],
            [
                'nombre' => 'Organizador Planazo',
                'password' => Hash::make('12345678'),
                'es_root' => false,
                'baneado' => false,
                'motivo_baneo' => null,
                'baneado_hasta' => null,
            ]
        );

        // ✅ Crear organizador asociado al usuario
        $organizador = Organizador::firstOrCreate(
            ['usuario_id' => $usuario->id],
            [
                'contacto' => '099123456',
            ]
        );

        // ✅ Lista de eventos por defecto con su categoría y ubicación asociada
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
                    'nombre' => 'footbool.jpg',
                    'ruta'   => 'imagenes/footbool.jpg',
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

        // ✅ Crear cada evento con su imagen, fecha, categoría y ubicación
        foreach ($eventosDefault as $data) {
            $evento = Evento::firstOrCreate(
                ['titulo' => $data['titulo']],
                [
                    'descripcion' => $data['descripcion'],
                    'organizador_id' => $organizador->id,
                    'latitud' => $data['latitud'],
                    'longitud' => $data['longitud'],
                ]
            );

            // Crear imagen asociada si no existe
            if (!$evento->imagen) {
                Imagen::firstOrCreate(
                    ['evento_id' => $evento->id],
                    [
                        'nombre' => $data['imagen']['nombre'],
                        'ruta'   => $data['imagen']['ruta'],
                    ]
                );
            }

            // ✅ Crear fecha por defecto (dentro de 7 días a las 20:00)
            FechaHora::firstOrCreate(
                [
                    'evento_id' => $evento->id,
                    'fecha_hora' => Carbon::now()->addDays(7)->setTime(20, 0, 0),
                ]
            );

            // ✅ Asociar categoría correspondiente
            $categoria = Categoria::firstOrCreate(['nombre' => $data['categoria']]);
            $evento->categorias()->syncWithoutDetaching([$categoria->id]);
        }

        $this->command->info('✅ Usuario, organizador, eventos, imágenes, fechas, categorías y coordenadas creados correctamente.');
    }
}
