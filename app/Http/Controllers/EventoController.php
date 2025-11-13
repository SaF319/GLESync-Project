<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Categoria;
use App\Models\FechaHora;
use App\Models\Imagen;
use App\Models\Organizador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventoController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $usuario = Auth::user();
            $organizador = $usuario->organizador;

            $eventos = $organizador
                ? $organizador->eventos()->with(['categorias', 'fechasHoras', 'imagen'])->get()
                : collect();
            return view('dashboard', compact('eventos'));
        }

        $eventos = Evento::with(['categorias', 'fechasHoras', 'imagen'])
            ->whereNotNull('organizador_id')
            ->get();

        return view('eventos.index', compact('eventos'));
    }

    public function create()
    {
        $usuario = Auth::user();

        if (!$usuario->organizador) {
            return view('eventos.no_organizador');
        }

        $categorias = Categoria::all();
        return view('eventos.create', compact('categorias'));
    }

    public function hacerseOrganizador(Request $request)
    {
        $usuario = Auth::user();

        if (!$usuario->organizador) {
            Organizador::create([
                'usuario_id' => $usuario->id,
                'contacto' => $request->contacto ?? $usuario->email,
            ]);
        }

        return redirect()->route('eventos.create')
            ->with('success', 'Ya eres organizador, ahora puedes crear eventos.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:200',
            'descripcion' => 'required|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'fecha_hora' => 'required|date',
            'categorias' => 'required|array',
            'categorias.*' => 'exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $usuario = Auth::user();
        $organizador = $usuario->organizador;

        if (!$organizador) {
            return redirect()->route('eventos.create')
                ->withErrors('Debes registrarte como organizador antes de crear un evento.');
        }

        $latitud = $request->latitud ?? 0;
        $longitud = $request->longitud ?? 0;

        DB::statement('CALL crear_evento(?, ?, ?, ?, ?, ?)', [
            $request->titulo,
            $request->descripcion,
            $request->fecha_hora,
            $latitud,
            $longitud,
            $organizador->id
        ]);

        $evento = Evento::latest('id')->first();
        $evento->categorias()->attach($request->categorias);

        if ($request->hasFile('imagen')) {
            $this->guardarImagen($request, $evento);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Evento creado exitosamente!');
    }

    // 🔹 Método show actualizado para permitir ver eventos de otros organizadores
    public function show($id)
    {
        $evento = Evento::with(['comentarios.usuario', 'categorias', 'fechasHoras', 'imagen'])
            ->findOrFail($id);

        $comentarios = $evento->comentarios;
        return view('eventos.show', compact('evento', 'comentarios'));
    }

    public function edit($id)
    {
        $usuario = Auth::user();
        $organizador = $usuario->organizador;

        if (!$organizador) {
            return redirect()->route('dashboard')
                ->withErrors('Debes ser organizador para editar eventos.');
        }

        $evento = Evento::where('id', $id)
            ->where('organizador_id', $organizador->id)
            ->firstOrFail();

        $todasCategorias = Categoria::all();
        $eventoCategorias = $evento->categorias->pluck('id')->toArray();
        $fechaHora = $evento->fechasHoras->first();

        return view('eventos.edit', compact('evento', 'eventoCategorias', 'fechaHora', 'todasCategorias'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Auth::user();
        $organizador = $usuario->organizador;

        if (!$organizador) {
            return redirect()->route('dashboard')
                ->withErrors('Debes ser organizador para actualizar eventos.');
        }

        $evento = Evento::where('id', $id)
            ->where('organizador_id', $organizador->id)
            ->firstOrFail();

        $request->validate([
            'titulo' => 'required|string|max:200',
            'descripcion' => 'required|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'fecha_hora' => 'required|date',
            'categorias' => 'required|array',
            'categorias.*' => 'exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $evento->update([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
        ]);

        $this->actualizarFechaHora($evento, $request->fecha_hora);
        $evento->categorias()->sync($request->categorias);

        if ($request->hasFile('imagen')) {
            $this->reemplazarImagen($request, $evento);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Evento actualizado exitosamente!');
    }

    public function destroy($id)
    {
        $usuario = Auth::user();
        $organizador = $usuario->organizador;

        if (!$organizador) {
            return redirect()->route('dashboard')
                ->withErrors('Debes ser organizador para eliminar eventos.');
        }

        $evento = Evento::where('id', $id)
            ->where('organizador_id', $organizador->id)
            ->firstOrFail();

        if ($evento->imagen) {
            $this->eliminarImagen($evento->imagen);
        }

        $evento->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Evento eliminado exitosamente!');
    }

    private function guardarImagen(Request $request, Evento $evento): void
    {
        $imagen = $request->file('imagen');
        $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
        $directorio = public_path('imagenes');

        if (!file_exists($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $ruta = 'imagenes/' . $nombreImagen;
        $imagen->move($directorio, $nombreImagen);

        Imagen::create([
            'evento_id' => $evento->id,
            'nombre' => $nombreImagen,
            'ruta' => $ruta
        ]);
    }

    private function reemplazarImagen(Request $request, Evento $evento): void
    {
        if ($evento->imagen) {
            $this->eliminarImagen($evento->imagen);
        }

        $this->guardarImagen($request, $evento);
    }

    private function eliminarImagen(Imagen $imagen): void
    {
        $rutaImagen = public_path($imagen->ruta);
        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
        $imagen->delete();
    }

    private function actualizarFechaHora(Evento $evento, string $fechaHora): void
    {
        $fechaHoraModel = $evento->fechasHoras->first();
        if ($fechaHoraModel) {
            $fechaHoraModel->update(['fecha_hora' => $fechaHora]);
        } else {
            FechaHora::create([
                'evento_id' => $evento->id,
                'fecha_hora' => $fechaHora
            ]);
        }
    }
}
