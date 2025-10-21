<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evento;
use App\Models\Imagen;

class EventosDefaultSeeder extends Seeder
{
    public function run(): void
    {
        $eventosDefault = [
            [
                'titulo' => 'Recital de Rock',
                'descripcion' => 'Concierto en el estadio este viernes.',
                'imagen' => [
                    'nombre' => 'recitales.jpg',
                    'ruta'   => 'imagenes/recitales.jpg',
                ],
            ],
            [
                'titulo' => 'Campeonato de Fútbol',
                'descripcion' => 'Partido final del torneo local.',
                'imagen' => [
                    'nombre' => 'footbool.jpg',
                    'ruta'   => 'imagenes/footbool.jpg',
                ],
            ],
            [
                'titulo' => 'Muestra de Arte',
                'descripcion' => 'Exposición de artistas locales en el museo.',
                'imagen' => [
                    'nombre' => 'muestraArte.jpg',
                    'ruta'   => 'imagenes/muestraArte.jpg',
                ],
            ],
        ];

        foreach ($eventosDefault as $data) {
            // Crea el evento solo si no existe (usa título como referencia)
            $evento = Evento::firstOrCreate(
                ['titulo' => $data['titulo']],
                [
                    'descripcion'    => $data['descripcion'],
                    'organizador_id' => null, // o algún valor por defecto si querés
                ]
            );

            // ✅ Verificamos si ya tiene una imagen asociada
            if (!$evento->imagen) {
                Imagen::create([
                    'evento_id' => $evento->id,
                    'nombre'    => $data['imagen']['nombre'],
                    'ruta'      => $data['imagen']['ruta'],
                ]);
            }
        }
    }
}
