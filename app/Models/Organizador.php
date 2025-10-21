<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizador extends Model
{
    use HasFactory;

    protected $table = 'organizadores';

    protected $fillable = [
        'usuario_id',
        'contacto'
    ];

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id');
    }

    // Relación con eventos
    public function eventos()
    {
        return $this->hasMany(Evento::class, 'organizador_id');
    }

    // Relación con creaciones
    public function creaciones()
    {
        return $this->hasMany(Creacion::class, 'organizador_id');
    }
}
