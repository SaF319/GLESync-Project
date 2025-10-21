<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function index()
    {
        // 🔹 Convertimos eventos de BD a arrays normales
        $eventosUsuarios = Evento::with(['categorias', 'fechasHoras', 'imagen'])
            ->get()
            ->map(function ($evento) {
                return [
                    'titulo' => $evento->titulo,
                    'descripcion' => $evento->descripcion,
                    'estado' => $evento->fechasHoras->first() && $evento->fechasHoras->first()->fecha_hora > now()
                        ? 'futuro'
                        : 'pasado',
                    'imagen_url' => $evento->imagen ? asset($evento->imagen->ruta) : null,
                ];
            })
            ->values()
            ->toArray(); // 👈 forzamos array plano

        // 🔹 Eventos por defecto en array
        $eventosDefault = [
            [
                'titulo' => 'Recital de Rock',
                'descripcion' => 'Concierto en el estadio este viernes.',
                'estado' => 'futuro',
                'imagen_url' => asset('imagenes/recitales.jpg'),
            ],
            [
                'titulo' => 'Campeonato de Fútbol',
                'descripcion' => 'Partido final del torneo local.',
                'estado' => 'presente',
                'imagen_url' => asset('imagenes/footbool.jpg'),
            ],
            [
                'titulo' => 'Muestra de Arte',
                'descripcion' => 'Exposición de artistas locales en el museo.',
                'estado' => 'futuro',
                'imagen_url' => asset('imagenes/muestraArte.jpg'),
            ],
        ];

        // 🔹 Convertimos todo a una Collection
        $eventos = collect($eventosUsuarios)->merge($eventosDefault);

        // --- PAGINACIÓN MANUAL ---
        $perPage = 9;
        $currentPage = request()->get('page', 1);
        $items = $eventos instanceof Collection ? $eventos : Collection::make($eventos);

        $eventosPaginados = new LengthAwarePaginator(
            $items->forPage($currentPage, $perPage),
            $items->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('home', ['eventos' => $eventosPaginados]);
    }
}
