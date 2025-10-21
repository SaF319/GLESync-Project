<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comentario;

class ComentarioController extends Controller
{
    public function store(Request $request)
    {
        // Verificar que el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para comentar');
        }

        // Validar los datos del formulario
        $validated = $request->validate([
            "comentario" => "required|string|max:500",
            "evento_id" => "required|exists:eventos,id"
        ]);

        $usuarioId = Auth::id();
        $eventoId = $validated['evento_id'];

        try {
            // Buscar si ya existe un comentario de este usuario en este evento
            $comentarioExistente = Comentario::where('evento_id', $eventoId)
                                            ->where('usuario_id', $usuarioId)
                                            ->first();

            if ($comentarioExistente) {
                // Si existe, actualizar el comentario
                $comentarioExistente->update([
                    'comentario' => $validated['comentario']
                ]);
                $mensaje = 'Comentario actualizado correctamente';
            } else {
                // Si no existe, crear nuevo comentario
                Comentario::create([
                    "comentario" => $validated['comentario'],
                    "usuario_id" => $usuarioId,
                    "evento_id" => $eventoId
                ]);
                $mensaje = 'Comentario agregado correctamente';
            }

            return redirect()->back()->with('success', $mensaje);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al guardar el comentario: ' . $e->getMessage());
        }
    }
}
