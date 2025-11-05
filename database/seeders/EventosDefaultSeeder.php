<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuarios;
use App\Models\Organizador;
use App\Models\Evento;
use App\Models\Imagen;
use Illuminate\Support\Facades\Hash;

class EventosDefaultSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = Usuarios::firstOrCreate(
            ['email' => 'organizador@planazo.com'],
            [
                'nombre' => 'Organizador Planazo',
                'password' => Hash::make('12345678'),
            ]
        );

        $organizador = Organizador::firstOrCreate(
            ['usuario_id' => $usuario->id],
            [
                'contacto' => '099123456',
            ]
        );

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
            $evento = Evento::firstOrCreate(
                ['titulo' => $data['titulo']],
                [
                    'descripcion' => $data['descripcion'],
                    'organizador_id' => $organizador->id,
                ]
            );

            if (!$evento->imagen) {
                Imagen::firstOrCreate(
                    ['evento_id' => $evento->id],
                    [
                        'nombre' => $data['imagen']['nombre'],
                        'ruta' => $data['imagen']['ruta'],
                    ]
                );
            }
        }

        $this->command->info('✅ Usuario, organizador, eventos e imágenes creados correctamente.');
    }
}
