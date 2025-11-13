<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'evento_id',
        'organizador_id'
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function organizador()
    {
        return $this->belongsTo(Organizador::class, 'organizador_id');
    }
}
