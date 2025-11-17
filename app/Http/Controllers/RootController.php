<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\Comentarios;
use App\Models\Evento;

class RootController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    /** LISTA COMPLETA DE USUARIOS */
    public function usuariosIndex()
    {
        $usuarios = Usuarios::with(['comentarios'])->get();

        return view('admin.usuarios', compact('usuarios'));
    }

    /** BANEAR / DESBANEAR */
    public function toggleBaneo($id)
    {
        $usuario = Usuarios::findOrFail($id);
        $usuario->baneado = !$usuario->baneado;
        $usuario->save();

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    /** LISTA COMPLETA DE COMENTARIOS */
    public function comentariosIndex()
    {
        $comentarios = Comentarios::with(['usuario', 'evento'])
            ->withTrashed()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.comentarios', compact('comentarios'));
    }

    /** RESTAURAR COMENTARIO */
    public function comentarioRestore($id)
    {
        Comentarios::withTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'Comentario restaurado.');
    }

    /** LISTA COMPLETA DE EVENTOS (si lo querés en el panel root) */
    public function eventosIndex()
    {
        $eventos = Evento::withTrashed()->get();
        return view('admin.eventos', compact('eventos'));
    }

    /** VER TODOS LOS COMENTARIOS DE UN USUARIO */
    public function usuarioComentarios($id)
    {
        $usuario = Usuarios::findOrFail($id);

        $comentarios = Comentarios::withTrashed()
            ->where('id_usuario', $id)
            ->with('evento')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.usuario_comentarios', compact('usuario', 'comentarios'));
    }
}
