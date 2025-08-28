<?php

use Illuminate\Support\Facades\Route;

/*
Route::get('/', function () {
    return view('home');
});
*/

Route::get('/', function () {
    $eventos = [
        [
            'titulo' => 'Recital de Rock',
            'descripcion' => 'Concierto en el estadio este viernes.',
            'estado' => 'futuro',
            'imagen_url' => asset('imagenes/recitales.jpg')
        ],
        [
            'titulo' => 'Campeonato de Fútbol',
            'descripcion' => 'Partido final del torneo local.',
            'estado' => 'presente',
            'imagen_url' => asset('imagenes/footbool.jpg')
        ],
        [
            'titulo' => 'Muestra de Arte',
            'descripcion' => 'Exposición de artistas locales en el museo.',
            'estado' => 'futuro',
            'imagen_url' => asset('imagenes/muestraArte.jpg')
        ],
    ];

    return view('home', compact('eventos'));
});

// Ruta para mostrar login
Route::get('/login', function () {
    return view('login');
});

// Ruta para mostrar login
Route::get('/registro', function () {
    return view('registro');
});
