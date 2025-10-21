<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();


        if ($usuario->organizador) {
            $eventos = $usuario->organizador->eventos()
                ->with(['categorias', 'fechasHoras', 'imagen'])
                ->get();
        } else {
            $eventos = collect();
        }

        return view('dashboard', compact('eventos'));
    }
}
