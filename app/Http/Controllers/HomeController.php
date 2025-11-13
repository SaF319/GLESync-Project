<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private const PER_PAGE = 9;

    public function index()
    {
        $eventosUsuarios = $this->obtenerEventosUsuarios();
        $eventosDefaultBD = $this->obtenerEventosDefault();
        $eventos = collect($eventosUsuarios)->merge($eventosDefaultBD);

        $currentPage = request()->get('page', 1);
        $items = Collection::make($eventos);

        $eventosPaginados = new LengthAwarePaginator(
            $items->forPage($currentPage, self::PER_PAGE),
            $items->count(),
            self::PER_PAGE,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('home', ['eventos' => $eventosPaginados]);
    }

    public function buscar(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:255',
            'page' => 'sometimes|integer|min:1'
        ]);

        $searchTerm = $request->input('q');
        $page = $request->input('page', 1);

        $eventosResultados = Evento::where(function($query) use ($searchTerm) {
            $query->where('titulo', 'LIKE', "%{$searchTerm}%")
                ->orWhere('descripcion', 'LIKE', "%{$searchTerm}%");
        })
        ->with(['categorias', 'fechasHoras', 'imagen'])
        ->get()
        ->map(fn($evento) => $this->mapearEvento($evento, $searchTerm));

        $totalResultados = $eventosResultados->count();
        $eventosResultados = $eventosResultados->forPage($page, self::PER_PAGE);

        return view('resultados', [
            'eventos' => $eventosResultados,
            'terminoBusqueda' => $searchTerm,
            'paginaActual' => $page,
            'porPagina' => self::PER_PAGE,
            'totalResultados' => $totalResultados,
            'totalPaginas' => ceil($totalResultados / self::PER_PAGE),
            'usuarioAutenticado' => Auth::check()
        ]);
    }

    private function obtenerEventosUsuarios(): array
    {
        return Evento::with(['categorias', 'fechasHoras', 'imagen'])
            ->whereNotNull('organizador_id')
            ->get()
            ->map(fn($evento) => $this->mapearEventoBasico($evento))
            ->values()
            ->toArray();
    }

    private function obtenerEventosDefault(): array
    {
        return Evento::with('imagen')
            ->whereNull('organizador_id')
            ->get()
            ->map(fn($evento) => $this->mapearEventoBasico($evento))
            ->values()
            ->toArray();
    }

    private function mapearEventoBasico($evento): array
    {
        return [
            'id' => $evento->id,
            'titulo' => $evento->titulo,
            'descripcion' => $evento->descripcion,
            'estado' => $this->determinarEstado($evento),
            'imagen_url' => $evento->imagen ? asset($evento->imagen->ruta) : null,
        ];
    }

    private function mapearEvento($evento, string $searchTerm): array
    {
        return array_merge($this->mapearEventoBasico($evento), [
            'fechas_evento' => $evento->fechasHoras->pluck('fecha_hora')->implode(', '),
            'categorias' => $evento->categorias->pluck('nombre')->implode(', '),
            'relevancia' => 2,
            'total_comentarios' => $evento->comentarios->count()
        ]);
    }

    private function determinarEstado($evento): string
    {
        $fechaHora = $evento->fechasHoras->first();
        return $fechaHora && $fechaHora->fecha_hora > now() ? 'futuro' : 'pasado';
    }
}
