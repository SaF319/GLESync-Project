<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaccion extends Model
{
    use HasFactory;

    protected $fillable = [
        'evento_id',
        'usuario_id',
        'comentarios',
        'puntaje',
        'visitado'
    ];

    // Relación con evento
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id');
    }
}
