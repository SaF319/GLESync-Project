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

        // === NUEVOS CAMPOS === //
        'es_root',
        'baneado',
        'motivo_baneo',
        'baneado_hasta',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ============================
    //        RELACIONES
    // ============================

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

    // ============================
    //   MÉTODOS DEL USUARIO ROOT
    // ============================

    // ¿Es root?
    public function esRoot()
    {
        return $this->es_root === 1;
    }

    // ¿Está baneado?
    public function estaBaneado()
    {
        return $this->baneado === 1;
    }

    // ¿El baneo está activo?
    public function baneoActivo()
    {
        if (!$this->estaBaneado()) {
            return false;
        }

        // Si hay fecha de vencimiento del baneo
        if ($this->baneado_hasta && now()->lt($this->baneado_hasta)) {
            return true;
        }

        // Si baneado = 1 y no tiene fecha → baneo permanente
        return $this->baneado === 1;
    }
}
