<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        // Si el usuario tiene organizador, traemos sus eventos con relaciones
        if ($usuario->organizador) {
            $eventos = $usuario->organizador->eventos()
                ->with(['categorias', 'fechasHoras', 'imagen'])
                ->get();
        } else {
            $eventos = collect(); // colección vacía si no es organizador
        }

        return view('dashboard', compact('eventos'));
    }
}
