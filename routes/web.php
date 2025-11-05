<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ComentarioController;

Route::get('/', [HomeController::class, 'index'])->name('home');

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



Route::post('/comentario', [ComentarioController::class, 'store'])->name('comentario.store');
Route::get('/eventos/{evento}/comentarios', [ComentarioController::class, 'index'])
->name('comentarios.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');
    Route::resource('eventos', EventoController::class);
    Route::post('/organizador/hacerse', [EventoController::class, 'hacerseOrganizador'])
    ->name('organizador.hacerse');
});


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/buscar', [HomeController::class, 'buscar'])->name('home.buscar');

Route::get('/eventos', [EventoController::class, 'index']);
Route::get('/eventos/{id}', [EventoController::class, 'show']);

