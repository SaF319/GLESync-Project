<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Buscar eventos usando el procedimiento almacenado
     */
    public function buscar(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:255',
            'page' => 'sometimes|integer|min:1'
        ]);

        $searchTerm = $request->input('q');
        $page = $request->input('page', 1);
        $perPage = 9;

        // ✅ USAR PROCEDIMIENTO ALMACENADO para búsqueda en TODOS los eventos
        // SIEMPRE buscar en todos los eventos, sin importar si está autenticado
        $eventosResultados = Evento::where(function($query) use ($searchTerm) {
            $query->where('titulo', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('descripcion', 'LIKE', "%{$searchTerm}%");
        })
        ->with(['categorias', 'fechasHoras', 'imagen'])
        ->get()
        ->map(function ($evento) {
            return [
                'id' => $evento->id,
                'titulo' => $evento->titulo,
                'descripcion' => $evento->descripcion,
                'estado' => $evento->fechasHoras->first() && $evento->fechasHoras->first()->fecha_hora > now()
                    ? 'futuro'
                    : 'pasado',
                'imagen_url' => $evento->imagen ? asset($evento->imagen->ruta) : null,
                'fechas_evento' => $evento->fechasHoras->pluck('fecha_hora')->implode(', '),
                'categorias' => $evento->categorias->pluck('nombre')->implode(', '),
                'relevancia' => 2, // Valor por defecto para búsqueda general
                'total_comentarios' => $evento->comentarios->count()
            ];
        });

        $totalResultados = $eventosResultados->count();
        $eventosResultados = $eventosResultados->forPage($page, $perPage);

        return view('resultados', [
            'eventos' => $eventosResultados,
            'terminoBusqueda' => $searchTerm,
            'paginaActual' => $page,
            'porPagina' => $perPage,
            'totalResultados' => $totalResultados,
            'totalPaginas' => ceil($totalResultados / $perPage),
            'usuarioAutenticado' => Auth::check()
        ]);
    }
}
