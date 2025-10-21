<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'latitud',
        'longitud',
        'descripcion',
        'organizador_id'
    ];

    // Relación con organizador
    public function organizador()
    {
        return $this->belongsTo(Organizador::class, 'organizador_id');
    }

    // Relación con fechas y horas
    public function fechasHoras()
    {
        return $this->hasMany(FechaHora::class, 'evento_id');
    }

    // Relación con comentarios
    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'evento_id');
    }

    // Relación con interacciones
    public function interacciones()
    {
        return $this->hasMany(Interaccion::class, 'evento_id');
    }

    // Relación con categorías (muchos a muchos)
    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_evento', 'evento_id', 'categoria_id');
    }

    // Relación con creaciones
    public function creaciones()
    {
        return $this->hasMany(Creacion::class, 'evento_id');
    }

    // Relación con imágenes (uno a uno)
    public function imagen()
    {
        return $this->hasOne(Imagen::class, 'evento_id');
    }
}
