<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comentario;

class ComentarioController extends Controller
{
    // Guardar un comentario
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para comentar.');
        }

        $validated = $request->validate([
            "comentario" => "required|string|max:500",
            "evento_id" => "required|exists:eventos,id"
        ]);

        $usuarioId = Auth::id();
        $eventoId = $validated['evento_id'];

        // Verificar si el usuario ya tiene un comentario en este evento
        $comentarioExistente = Comentario::where('evento_id', $eventoId)
                                         ->where('usuario_id', $usuarioId)
                                         ->first();

        if ($comentarioExistente) {
            return redirect()->back()->with('error', 'Ya has comentado este evento. No puedes comentar más de una vez, pero puedes editar o eliminar tu comentario.');
        }

        Comentario::create([
            "comentario" => $validated['comentario'],
            "usuario_id" => $usuarioId,
            "evento_id" => $eventoId
        ]);

        return redirect()->back()->with('success', 'Comentario agregado correctamente.');
    }

    // Editar un comentario
    public function edit($id)
    {
        $comentario = Comentario::findOrFail($id);

        if ($comentario->usuario_id !== Auth::id()) {
            return redirect()->back()->with('error', 'No tienes permiso para editar este comentario.');
        }

        return view('comentarios.edit', compact('comentario'));
    }

    // Actualizar un comentario
    public function update(Request $request, $id)
    {
        $comentario = Comentario::findOrFail($id);

        if ($comentario->usuario_id !== Auth::id()) {
            return redirect()->back()->with('error', 'No tienes permiso para actualizar este comentario.');
        }

        $validated = $request->validate([
            "comentario" => "required|string|max:500"
        ]);

        $comentario->update([
            'comentario' => $validated['comentario']
        ]);

        return redirect()->back()->with('success', 'Comentario actualizado correctamente.');
    }

    // Eliminar un comentario
    public function destroy($id)
    {
        $comentario = Comentario::findOrFail($id);

        if ($comentario->usuario_id !== Auth::id()) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar este comentario.');
        }

        $comentario->delete();

        return redirect()->back()->with('success', 'Comentario eliminado correctamente.');
    }
}
