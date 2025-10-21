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
        // 🔹 Eventos creados por usuarios (todos los que tienen organizador)
        $eventosUsuarios = Evento::with(['categorias', 'fechasHoras', 'imagen'])
            ->whereNotNull('organizador_id')
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
            ->toArray();

        // 🔹 Eventos por defecto (creados por el sistema, organizador_id = NULL)
        $eventosDefaultBD = Evento::with('imagen')
            ->whereNull('organizador_id')
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
            ->toArray();

        // 🔹 Combinar todos los eventos
        $eventos = collect($eventosUsuarios)->merge($eventosDefaultBD);

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
