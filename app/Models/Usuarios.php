<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuarios extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
    'nombre',
    'email',
    'google_id',
    'password',
    'google2fa_secret',
    'is_2fa_enabled',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function organizador()
    {
        return $this->hasOne(Organizador::class, 'usuario_id');
    }

    public function participante()
    {
        return $this->hasOne(Participante::class, 'usuario_id');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'usuario_id');
    }

    public function interacciones()
    {
        return $this->hasMany(Interaccion::class, 'usuario_id');
    }

    public function preferencias()
    {
        return $this->hasMany(Preferencia::class, 'usuario_id');
    }
}
