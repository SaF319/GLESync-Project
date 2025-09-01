<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuarios extends Authenticatable
{
    use Notifiable;

    // Nombre de la tabla
    protected $table = 'usuarios';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Campos ocultos para arrays o JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Conversión automática de tipos
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
