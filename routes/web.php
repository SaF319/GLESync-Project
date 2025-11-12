<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\GoogleController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', fn() => view('login'))->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'validacion'])->name('login.process')->middleware('guest');

Route::get('/registro', fn() => view('registro'))->name('register.form')->middleware('guest');
Route::post('/registro', [AuthController::class, 'registro'])->name('register.process')->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::post('/comentario', [ComentarioController::class, 'store'])->name('comentario.store');
Route::get('/eventos/{evento}/comentarios', [ComentarioController::class, 'index'])->name('comentarios.index');

Route::get('/buscar', [HomeController::class, 'buscar'])->name('home.buscar');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('eventos', EventoController::class)->except(['index', 'show']);
    Route::post('/organizador/hacerse', [EventoController::class, 'hacerseOrganizador'])->name('organizador.hacerse');
    Route::get('/perfil/2fa', [TwoFactorController::class, 'setup'])->name('2fa.setup');
    Route::post('/perfil/2fa/enable', [TwoFactorController::class, 'enable'])->name('2fa.enable');
});

Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');
Route::get('/eventos/{id}', [EventoController::class, 'show'])->name('eventos.show');

Route::get('/2fa', [TwoFactorController::class, 'index'])->name('2fa.index');
Route::post('/2fa', [TwoFactorController::class, 'verify'])->name('2fa.verify');

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
