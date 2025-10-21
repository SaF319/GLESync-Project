<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaEvento extends Model
{
    use HasFactory;

    protected $fillable = [
        'categoria_id',
        'evento_id'
    ];

    // Relación con categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    // Relación con evento
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }
}
