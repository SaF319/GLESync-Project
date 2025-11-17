<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\Evento;
use App\Models\Comentario;
use Illuminate\Routing\Controller as BaseController;

class RootController extends BaseController
{
    public function __construct()
    {
        // Middleware root y auth
        $this->middleware(['auth', 'root']);
    }

    // Dashboard root
    public function index()
    {
        return view('admin.dashboard');
    }

    // ===========================
    //      USUARIOS
    // ===========================
    public function usuariosIndex()
    {
        $usuarios = Usuarios::paginate(20);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function toggleBaneo(Request $request, $id)
    {
        $usuario = Usuarios::findOrFail($id);

        if ($usuario->es_root) {
            return redirect()->back()->withErrors('No puedes banear al usuario root.');
        }

        $usuario->baneado = !$usuario->baneado;

        if ($usuario->baneado) {
            $usuario->motivo_baneo = $request->motivo_baneo ?? 'Sin motivo especificado';
        } else {
            $usuario->motivo_baneo = null;
            $usuario->baneado_hasta = null;
        }

        $usuario->save();

        return redirect()->back()->with('success', 'Estado de baneo actualizado.');
    }

    // ===========================
    //      EVENTOS
    // ===========================
    public function eventosIndex()
    {
        $eventos = Evento::with('organizador.usuario')->paginate(20);
        return view('admin.eventos.index', compact('eventos'));
    }

    // ===========================
    //      COMENTARIOS
    // ===========================
    public function comentariosIndex()
    {
        $comentarios = Comentario::with(['usuario','evento'])
                                    ->withTrashed()
                                    ->paginate(20);

        return view('admin.comentarios.index', compact('comentarios'));
    }

    public function comentarioRestore($id)
    {
        $comentario = Comentario::withTrashed()->findOrFail($id);
        $comentario->restore();

        return redirect()->back()->with('success','Comentario restaurado correctamente.');
    }

    // ===========================
    //   COMENTARIOS DE USUARIO
    // ===========================
    public function usuarioComentarios($id)
    {
        $usuario = Usuarios::findOrFail($id);

        $comentarios = Comentario::withTrashed()
                                 ->where('id_usuario', $id)
                                 ->with('evento')
                                 ->orderBy('created_at','desc')
                                 ->paginate(20);

        return view('admin.usuarios.comentarios', compact('usuario','comentarios'));
    }
}
