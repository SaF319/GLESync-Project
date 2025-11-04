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
    /**
     * Obtener los eventos del usuario autenticado
     */
    public function index()
    {
        $usuario = Auth::user();
        $organizador = $usuario->organizador;

        // Obtenemos los eventos directamente desde la relación
        $eventos = $organizador
            ? $organizador->eventos()->with(['categorias', 'fechasHoras', 'imagen'])->get()
            : collect(); // colección vacía si no es organizador

        return view('dashboard', compact('eventos'));
    }

    /**
     * Formulario de creación de evento
     */
    public function create()
    {
        $usuario = Auth::user();

        // Si no es organizador → mostramos mini vista
        if (!$usuario->organizador) {
            return view('eventos.no_organizador');
        }

        // Si ya es organizador → mostramos formulario de eventos
        $categorias = Categoria::all();
        return view('eventos.create', compact('categorias'));
    }

    /**
     * Convertir al usuario en organizador
     */
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

    /**
     * Guardar nuevo evento usando procedimiento
     */
    public function store(Request $request)
    {
        // Validaciones
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

        $usuario = Auth::user();
        $organizador = $usuario->organizador;

        if (!$organizador) {
            return redirect()->route('eventos.create')
                ->withErrors('Debes registrarte como organizador antes de crear un evento.');
        }

        // Llamamos al procedimiento almacenado
        DB::statement('CALL crear_evento(?, ?, ?, ?, ?, ?)', [
            $request->titulo,
            $request->descripcion,
            $request->fecha_hora,
            $request->latitud ?? 0,
            $request->longitud ?? 0,
            $organizador->id
        ]);

        // Capturamos el último evento creado
        $evento = Evento::latest('id')->first();

        // Relación categorías
        $evento->categorias()->attach($request->categorias);

        // Manejar imagen
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time().'_'.$imagen->getClientOriginalName();

            if (!file_exists(public_path('imagenes'))) {
                mkdir(public_path('imagenes'), 0755, true);
            }

            $ruta = 'imagenes/'.$nombreImagen;
            $imagen->move(public_path('imagenes'), $nombreImagen);

            Imagen::create([
                'evento_id' => $evento->id,
                'nombre' => $nombreImagen,
                'ruta' => $ruta
            ]);
        }

        return redirect()->route('eventos.index')
            ->with('success', 'Evento creado exitosamente!');
    }

    /**
     * Mostrar un evento
     */
    public function show($id)
    {
        $usuario = Auth::user();
        $organizador = $usuario->organizador;

        $evento = Evento::findOrFail($id);
        $comentarios = $evento->comentarios;

        $evento = Evento::with(['comentarios.usuario', 'categorias', 'fechasHoras', 'imagen'])
            ->where('id', $id)
            ->where('organizador_id', $organizador->id)
            ->firstOrFail();

        return view('eventos.show', compact('evento', 'comentarios'));
    }

    /**
     * Editar un evento
     */
    public function edit($id)
    {
        $usuario = Auth::user();
        $organizador = $usuario->organizador;

        $evento = Evento::where('id', $id)
            ->where('organizador_id', $organizador->id)
            ->firstOrFail();

        // Traemos todas las categorías disponibles para evitar Undefined variable
        $todasCategorias = Categoria::all();

        $eventoCategorias = $evento->categorias->pluck('id')->toArray();
        $fechaHora = $evento->fechasHoras->first();

        return view('eventos.edit', compact('evento', 'eventoCategorias', 'fechaHora', 'todasCategorias'));
    }

    /**
     * Actualizar un evento
     */
    public function update(Request $request, $id)
    {
        $usuario = Auth::user();
        $organizador = $usuario->organizador;

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

        $fechaHora = $evento->fechasHoras->first();
        if ($fechaHora) {
            $fechaHora->update(['fecha_hora' => $request->fecha_hora]);
        } else {
            FechaHora::create([
                'evento_id' => $evento->id,
                'fecha_hora' => $request->fecha_hora
            ]);
        }

        $evento->categorias()->sync($request->categorias);

        if ($request->hasFile('imagen')) {
            if ($evento->imagen) {
                $rutaImagenAnterior = public_path($evento->imagen->ruta);
                if (file_exists($rutaImagenAnterior)) unlink($rutaImagenAnterior);
                $evento->imagen->delete();
            }

            $imagen = $request->file('imagen');
            $nombreImagen = time().'_'.$imagen->getClientOriginalName();

            if (!file_exists(public_path('imagenes'))) {
                mkdir(public_path('imagenes'), 0755, true);
            }

            $ruta = 'imagenes/'.$nombreImagen;
            $imagen->move(public_path('imagenes'), $nombreImagen);

            Imagen::create([
                'evento_id' => $evento->id,
                'nombre' => $nombreImagen,
                'ruta' => $ruta
            ]);
        }

        return redirect()->route('eventos.index')
            ->with('success', 'Evento actualizado exitosamente!');
    }

    /**
     * Eliminar un evento
     */
    public function destroy($id)
    {
        $usuario = Auth::user();
        $organizador = $usuario->organizador;

        $evento = Evento::where('id', $id)
            ->where('organizador_id', $organizador->id)
            ->firstOrFail();

        if ($evento->imagen) {
            $rutaImagen = public_path($evento->imagen->ruta);
            if (file_exists($rutaImagen)) unlink($rutaImagen);
            $evento->imagen->delete();
        }

        $evento->delete();

        return redirect()->route('eventos.index')
            ->with('success', 'Evento eliminado exitosamente!');
    }
}
