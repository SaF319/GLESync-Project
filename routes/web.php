<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Página de inicio
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
})->name('home');

// 🔹 Rutas de autenticación

// Login
Route::get('/login', function () {
    return view('login');
})->name('login.form')->middleware('guest'); // solo para no logueados

Route::post('/login', [AuthController::class, 'validacion'])
    ->name('login.process')
    ->middleware('guest');

// Registro
Route::get('/registro', function () {
    return view('registro');
})->name('register.form')->middleware('guest');

Route::post('/registro', [AuthController::class, 'registro'])
    ->name('register.process')
    ->middleware('guest');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth'); // solo usuarios autenticados

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth'); // solo usuarios autenticados
