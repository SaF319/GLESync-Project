<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;

// Página de inicio
Route::get('/', [HomeController::class, 'index'])->name('home');
/*
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
*/

Route::get('/login', function () {
    return view('login');
})->name('login')->middleware('guest');

Route::post('/login', [AuthController::class, 'validacion'])
    ->name('login.process')
    ->middleware('guest');

Route::get('/registro', function () {
    return view('registro');
})->name('register.form')->middleware('guest');

Route::post('/registro', [AuthController::class, 'registro'])
    ->name('register.process')
    ->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');


    /*
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('eventos', EventoController::class);

    // nueva ruta para hacerse organizador
    Route::post('/organizador/hacerse', [EventoController::class, 'hacerseOrganizador'])
        ->name('organizador.hacerse');
});


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/buscar', [HomeController::class, 'buscar'])->name('home.buscar');

Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');
Route::get('/eventos/{id}', [EventoController::class, 'show'])->name('eventos.show');
