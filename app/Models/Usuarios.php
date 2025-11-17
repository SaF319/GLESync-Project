<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class Usuarios extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'es_root',
        'baneado',
        'motivo_baneo',
        'baneado_hasta',
        'is_2fa_enabled',
        'google2fa_secret'
    ];

    protected $hidden = [
        'password',
        'google2fa_secret',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_2fa_enabled' => 'boolean',
        'es_root' => 'boolean',
        'baneado' => 'boolean',
        'baneado_hasta' => 'datetime',
    ];

    /**
     * Encriptar automáticamente el password al asignarlo
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * Relación con comentarios
     */
    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'id_usuario');
    }

    /**
     * Relación con organizador
     * ⚡ Corregido: la columna en la tabla es 'usuario_id'
     */
    public function organizador()
    {
        return $this->hasOne(Organizador::class, 'usuario_id');
    }

    /**
     * Check si es root
     */
    public function isRoot()
    {
        return $this->es_root;
    }

    /**
     * Scope de usuarios baneados
     */
    public function scopeBaneados($query)
    {
        return $query->where('baneado', true);
    }

    /**
     * Scope de usuarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('baneado', false);
    }

    /**
     * Check 2FA
     */
    public function has2FAEnabled()
    {
        return $this->is_2fa_enabled && $this->google2fa_secret;
    }
}
