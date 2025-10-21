<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre'
    ];

    // Relación con eventos (muchos a muchos)
    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'categoria_evento', 'categoria_id', 'evento_id');
    }

    // Relación con preferencias
    public function preferencias()
    {
        return $this->hasMany(Preferencia::class, 'categoria_id');
    }
}
